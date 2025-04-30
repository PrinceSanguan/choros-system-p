<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CTG;
use App\Models\CTGDocument;

class CTGDocumentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created document for a CTG member.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $ctgId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $ctgId)
    {
        // Find the CTG record
        $ctg = CTG::find($ctgId);

        if (!$ctg) {
            return response()->json([
                'success' => false,
                'message' => 'CTG member not found'
            ], 404);
        }

        // Validate the incoming request
        $validated = $request->validate([
            'documents' => 'required|array',
            'documents.*' => 'required|file|max:10240',
        ]);

        $documents = [];

        // Handle document uploads
        foreach ($request->file('documents') as $document) {
            $path = $document->store('ctg-documents', 'public');

            // Create document record
            $ctgDocument = new CTGDocument([
                'ctg_id' => $ctg->id,
                'file_path' => $path,
                'file_name' => $document->getClientOriginalName(),
                'file_type' => $document->getClientMimeType(),
            ]);

            $ctgDocument->save();
            $documents[] = $ctgDocument;
        }

        return response()->json([
            'success' => true,
            'message' => 'Documents uploaded successfully',
            'data' => $documents
        ]);
    }

    /**
     * Display the specified document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $document = CTGDocument::find($id);

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $document
        ]);
    }

    /**
     * Download the specified document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $document = CTGDocument::find($id);

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found on disk'
            ], 404);
        }

        $path = Storage::disk('public')->path($document->file_path);
        $fileName = $document->file_name ?: basename($document->file_path);

        return response()->download($path, $fileName);
    }

    /**
     * Remove the specified document from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $document = CTGDocument::find($id);

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Delete document record
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully'
        ]);
    }
}
