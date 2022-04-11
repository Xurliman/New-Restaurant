<?php
// u1573833
// a9UNaoM0xu7x8UbP
namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\ResponseController;
use Illuminate\Support\Facades\Storage;

class EventsController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();
        if (!AdminController::check($user)) {
            return ResponseController::error('Permission denied, not admin', 403);
        }
        $validation = Validator::make($request->all(), [
            'title' => 'required|unique:events,title|max:30',
            'description' => 'required|max:255',
            'images' => 'required',
        ]);
        if($validation->fails()){
            return ResponseController::error($validation->errors()->first(), 422);
        }

        Event::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'images'=>$request->images,
        ]);
        return ResponseController::success("Event has been successfully created", 201);
    }

    public function edit(Request $request, $event_id)
    {
        $user = $request->user();
        if (!AdminController::check($user)) {
            return ResponseController::error('Permission denied, not admin', 403);
        }
        $event = Event::where('id', $event_id)->first();
        if (!$event) {
            return ResponseController::error('Event that has this id, does not exist');
        }
        if (empty($request->all())) {
            return ResponseController::error("At least one field should be given to update");
        }

        $event->update($request->all());
        return ResponseController::success('Event has been successfully edited');
    }

    public function delete(Request $request, $event_id)
    {
        $user = $request->user();
        if (!AdminController::check($user)) {
            return ResponseController::error('Permission denied, not admin', 403);
        }
        if (!Event::find($event_id)) {
            return ResponseController::error('Event not found', 404);
        }
        
        Event::destroy($event_id);
        
        return ResponseController::success('Event has been successfully deleted');
    }

    public function view()
    {
        if (empty(Event::all())) {
            return ResponseController::error('Event list is empty', 404);
        }
        $data = Event::select('id', 'title', 'description', 'images')->get()->toArray();
        $events = [];
        foreach ($data as $item) {
            $events[] = [
                'id'=>$item['id'],
                'title'=> $item['title'],
                'description'=> $item['description'],
                'images'=> $item['images'],
            ];
        }
        return ResponseController::response($events);
    }
}
