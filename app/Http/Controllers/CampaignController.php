<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    public function index()
    {
        // جلب كل الحملات (للبحث والتصفية في JavaScript)
        $allCampaigns = Campaign::with('media')->orderBy('created_at', 'desc')->get();
        
        $totalCampaigns = Campaign::count();
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $plannedCampaigns = Campaign::where('status', 'planned')->count();
        $finishedCampaigns = Campaign::where('status', 'finished')->count();
        
        $allMedia = Media::all();
        
        // تحضير بيانات الحملات مع media_ids
        $campaignsData = [];
        foreach ($allCampaigns as $campaign) {
            $campaignData = $campaign->toArray();
            $campaignData['media_ids'] = $campaign->media->pluck('id')->toArray();
            $campaignData['media_count'] = $campaign->media->count();
            $campaignsData[] = $campaignData;
        }
        
        return view('campaigns.index', compact(
            'campaignsData', 'totalCampaigns', 'activeCampaigns', 
            'plannedCampaigns', 'finishedCampaigns', 'allMedia'
        ));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:active,planned,finished',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'progress' => 'nullable|integer|min:0|max:100',
                'media_ids' => 'nullable|json',
                'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
            ]);
            
            $mediaIds = json_decode($request->media_ids ?? '[]', true);
            $validated['media_count'] = count($mediaIds);
            
            if ($request->hasFile('background_image')) {
                $file = $request->file('background_image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('campaigns', $filename, 'public');
                $validated['background_image'] = Storage::url($path);
            }
            
            $campaign = Campaign::create($validated);
            
            if (!empty($mediaIds)) {
                $pivotData = [];
                foreach ($mediaIds as $index => $mediaId) {
                    $pivotData[$mediaId] = ['position' => $index];
                }
                $campaign->media()->attach($pivotData);
            }
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $campaign = Campaign::findOrFail($id);
            
            // Si c'est juste un changement de statut (Pause/Reprendre)
            if ($request->has('status') && !$request->hasFile('background_image') && !$request->has('title') && !$request->has('media_ids')) {
                $campaign->update(['status' => $request->status]);
                DB::commit();
                return response()->json(['success' => true]);
            }
            
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'sometimes|required|in:active,planned,finished',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'progress' => 'nullable|integer|min:0|max:100',
                'media_ids' => 'nullable|json',
                'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
            ]);
            
            if ($request->has('media_ids')) {
                $mediaIds = json_decode($request->media_ids, true);
                $validated['media_count'] = count($mediaIds);
                
                $pivotData = [];
                foreach ($mediaIds as $index => $mediaId) {
                    $pivotData[$mediaId] = ['position' => $index];
                }
                $campaign->media()->sync($pivotData);
            }
            
            if ($request->hasFile('background_image')) {
                if ($campaign->background_image) {
                    $oldPath = str_replace('/storage/', '', $campaign->background_image);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
                $file = $request->file('background_image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('campaigns', $filename, 'public');
                $validated['background_image'] = Storage::url($path);
            }
            
            $campaign->update($validated);
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $campaign = Campaign::findOrFail($id);
            $campaign->media()->detach();
            if ($campaign->background_image) {
                $oldPath = str_replace('/storage/', '', $campaign->background_image);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $campaign->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getCampaignMedia($id)
    {
        $campaign = Campaign::with('media')->findOrFail($id);
        return response()->json($campaign->media);
    }

    public function removeMediaFromCampaign($campaignId, $mediaId)
    {
        try {
            $campaign = Campaign::findOrFail($campaignId);
            $campaign->media()->detach($mediaId);
            $campaign->media_count = $campaign->media()->count();
            $campaign->save();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function getFileContent($id)
    {
        $media = Media::findOrFail($id);
        $filePath = storage_path('app/public/' . $media->file_path);
        
        $content = '';
        $extension = strtolower(pathinfo($media->file_path, PATHINFO_EXTENSION));
        
        if ($extension === 'txt') {
            $content = file_exists($filePath) ? file_get_contents($filePath) : '';
        } elseif ($extension === 'pdf') {
            // Pour PDF, on peut extraire le texte si la librarie est installée
            // Sinon on retourne un message indiquant qu'il faut télécharger
            $content = "[Fichier PDF - Téléchargez le fichier pour voir le contenu complet]";
        } elseif (in_array($extension, ['doc', 'docx'])) {
            $content = "[Document Word - Téléchargez le fichier pour voir le contenu complet]";
        } else {
            $content = file_exists($filePath) ? file_get_contents($filePath) : '';
        }
        
        return response()->json([
            'content' => $content,
            'filename' => $media->title . '.' . $extension,
            'type' => $media->type,
            'size' => $media->size,
            'created_at' => $media->created_at
        ]);
    }
}