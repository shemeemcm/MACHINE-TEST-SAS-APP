<?php

namespace App\Repositories;

use App\Interfaces\TicketAttachmentRepositoryInterface;
use App\Models\TicketAttachment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class TicketAttachmentRepository implements TicketAttachmentRepositoryInterface
{
    /**
     * Get paginated attachments for a ticket.
     */
    public function getAllByTicket(int $ticketId, int $perPage = 15): LengthAwarePaginator
    {
        return TicketAttachment::where('ticket_id', $ticketId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findById(int $id): ?TicketAttachment
    {
        return TicketAttachment::find($id);
    }

    /**
     * Store a new attachment.
     *
     * @param array $data   Core data (ticket_id, user_id, etc.)
     * @param \Illuminate\Http\UploadedFile $file
     */
    public function create(array $data, $file): TicketAttachment
    {
        // Store the file in the "ticket_attachments" disk (public) and keep the relative path.
        $filePath = $file->store('ticket_attachments', 'public');

        $attachment = new TicketAttachment();
        $attachment->ticket_id   = $data['ticket_id'];
        $attachment->user_id    = $data['user_id'];
        $attachment->file_name  = $file->getClientOriginalName();
        $attachment->file_path  = $filePath;
        $attachment->mime_type  = $file->getClientMimeType();
        $attachment->size       = $file->getSize();
        $attachment->save();

        return $attachment;
    }

    public function delete(int $id): bool
    {
        $attachment = $this->findById($id);
        if (! $attachment) {
            return false;
        }
        // Delete the physical file if it exists.
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }
        return $attachment->delete();
    }

    public function getFilePath(int $id): string
    {
        $attachment = $this->findById($id);
        return $attachment ? Storage::disk('public')->path($attachment->file_path) : '';
    }
}
?>
