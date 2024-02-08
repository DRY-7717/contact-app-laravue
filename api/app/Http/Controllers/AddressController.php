<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Models\Contact;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    //


    public function index(Contact $contact): JsonResponse
    {
        if ($contact->user_id !== auth()->user()->id) {
            throw new HttpResponseException(response()->json([
                "message" => "Data not found."
            ])->setStatusCode(404));
        }

        $address = Address::where('contact_id', $contact->id)->get();

        return (AddressResource::collection($address))->response()->setStatusCode(200);
    }
    public function store(CreateAddressRequest $request, Contact $contact): JsonResponse
    {

        $data = $request->validated();

        if ($contact->user_id !== auth()->user()->id) {
            throw new HttpResponseException(response()->json([
                "message" => "Data not found."
            ])->setStatusCode(404));
        }

        $data['contact_id'] = $contact->id;
        $address = Address::create($data);
        return (new AddressResource($address))->response()->setStatusCode(201);
    }
    public function show(Contact $contact, Address $address): AddressResource
    {



        if ($contact->user_id !== auth()->user()->id) {
            throw new HttpResponseException(response()->json([
                "message" => "Data not found."
            ])->setStatusCode(404));
        }

        if ($address->contact_id !== $contact->id) {
            throw new HttpResponseException(response()->json([
                "message" => "Data not found."
            ])->setStatusCode(404));
        }


        return new AddressResource($address);
    }

    public function update(UpdateAddressRequest $request, Contact $contact, Address $address): AddressResource
    {

        if ($contact->user_id !== auth()->user()->id) {
            throw new HttpResponseException(response()->json([
                "message" => "Data not found."
            ])->setStatusCode(404));
        }

        if ($address->contact_id !== $contact->id) {
            throw new HttpResponseException(response()->json([
                "message" => "Data not found."
            ])->setStatusCode(404));
        }

        $data = $request->validated();
        $address->update($data);

        return new AddressResource($address);
    }


    public function destroy(Contact $contact, Address $address): JsonResponse
    {
        if ($contact->user_id !== auth()->user()->id) {
            throw new HttpResponseException(response()->json([
                "message" => "Data not found."
            ])->setStatusCode(404));
        }

        if ($address->contact_id !== $contact->id) {
            throw new HttpResponseException(response()->json([
                "message" => "Data not found."
            ])->setStatusCode(404));
        }

        Address::destroy($address->id);

        return response()->json([
            'message' => 'Address has been deleted.'
        ], 200);
    }
}
