<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Note;

class NoteController extends Controller
{

    private $loggedUser = null;

    function __construct()
    {
        $this->loggedUser = auth()->user();
    }

    function list()
    {
        $notes = Note::all();
        $this->response['notes'] = [];

        foreach ($notes as $note) {
            $this->response['notes'][] = $this->noteData($note);
        }

        return $this->response();
    }

    function view($id)
    {
        $note = Note::find($id);

        if (! $note) {
            return $this->response(400);
        }

        $this->response['note'] = $this->noteData($note, true);
        return $this->response();
    }

    function create(Request $request)
    {
        $data = $request->only('title', 'body');
        $validator = $this->validator($data, [
            'title' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->response(422, $validator->errors());
        }

        if (! $this->loggedUser) {
            return $this->response(500);
        }

        Note::create([
            'title' => $data['title'],
            'body' => isset($data['body']) ? $data['body'] : '',
            'user_id' => $this->loggedUser->id,
        ]);

        return $this->response();
    }

    function update(Request $request, $id)
    {
        $note = Note::find($id);

        if (! $note) {
            return $this->response(400);
        }

        if (! $this->loggedUser) {
            return $this->response(500);
        }

        if ($note->user_id != $this->loggedUser->id) {
            return $this->response(401);
        }

        $data = [
            'title' => $request->title,
            'body' => $request->body ?? '',
        ];

        $validator = $this->validator($data, [
            'title' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return $this->response(422, $validator->errors());
        }

        if ($data['title']) {
            $note->title = $data['title'];
        }

        $note->body = $data['body'];

        $note->save();
        return $this->response();
    }

    function delete($id)
    {
        $note = Note::find($id);

        if (! $note) {
            return $this->response(400);
        }

        $note->delete();
        return $this->response();
    }

    private function noteData($note, bool $body = false)
    {
        $user = User::find($note->user_id);
        $userName = $user ? $user->name : null;

        $data = [
            'id' => $note->id,
            'title' => $note->title,
            'created_by' => $userName,
            'created_at' => $note->created_at,
            'updated_at' => $note->updated_at,
        ];

        if ($body) {
            $data['body'] = $note['body'];
        }

        return $data;
    }

    private function validator($data, $additional = [])
    {
        $rules = [
            'title' => ['max:256'],
        ];

        foreach ($additional as $key => $values) {
            if (! isset($rules[$key])) continue;

            foreach ($values as $value) {
                $rules[$key][] = $value;
            }
        }

        return Validator::make($data, $rules);
    }

}
