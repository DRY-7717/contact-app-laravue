<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\CreateContactResource;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{


    public function index(Request $request): ContactCollection
    {
        $user = Auth::user();
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);

        $contacts =  Contact::query()->where('user_id', $user->id);

        $contacts =  $contacts->where(function (Builder $builder) use ($request) {
            $name = $request->input('name');
            if ($name) {
                $builder->where(function (Builder $builder) use ($name) {
                    $builder->orWhere('first_name', 'like', '%' . $name . '%');
                    $builder->orWhere('last_name', 'like', '%' . $name . '%');
                });
            }

            $email = $request->input('email');
            if ($email) {
                $builder->where('email', 'like', '%' . $email . '%');
            }

            $phone = $request->input('phone');
            if ($phone) {
                $builder->where('phone', 'like', '%' . $phone . '%');
            }
        })->paginate(perPage: $size, page: $page);

        return new ContactCollection($contacts);
    }


   


    public function store(CreateContactRequest $request): JsonResponse
    {

        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;

        $contact = Contact::create($data);

        return (new CreateContactResource($contact))->response()->setStatusCode(201);
    }

  
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
