<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Resources\CreateContactResource;
use App\Models\Contact;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContactController extends Controller
{
    public function store(CreateContactRequest $request): JsonResponse
    {

        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;

        $contact = Contact::create($data);

        return (new CreateContactResource($contact))->response()->setStatusCode(201);
    }

    // public function show($id): CreateContactResource
    // {

    //     $data = Contact::where('id', $id)->where('user_id', auth()->user()->id)->first();

    //     if (!$data) {
    //         throw new HttpResponseException(response()->json([
    //             "errors" => [
    //                 "message" => [
    //                     "Data not found."
    //                 ]
    //             ]
    //         ])->setStatusCode(404));
    //     }

    //     return new CreateContactResource($data);
    // }
    public function show(Contact $contact): CreateContactResource
    {
        if ($contact->user_id !== auth()->user()->id) {
            throw new HttpResponseException(response()->json([
                "message" => "Data not found."

            ])->setStatusCode(404));
        }
        return new CreateContactResource($contact);
    }

    public function update(UpdateContactRequest $request, Contact $contact): CreateContactResource
    {
        if ($contact->user_id !== auth()->user()->id) {
            throw new HttpResponseException(response()->json([
                "message" => "Data not found."

            ])->setStatusCode(404));
        }
        $data = $request->validated();
        $contact->update($data);

        return new CreateContactResource($contact);
    }
    public function destroy(Contact $contact): JsonResponse
    {
        if ($contact->user_id !== auth()->user()->id) {
            throw new HttpResponseException(response()->json([
                "message" => "Data not found."

            ])->setStatusCode(404));
        }
        Contact::destroy($contact->id);

        return response()->json([
            "message" => "Contact has been deleted"
        ], 200);
    }
}
