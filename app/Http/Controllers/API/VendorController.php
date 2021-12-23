<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Repositories\VendorRepository;
use Illuminate\Http\Request;
use App\Http\Requests\API\CreateVendorRequest;
use App\Http\Requests\API\UpdateVendorRequest;


class VendorController extends AppBaseController
{
    private $vendorRepository;

    public function __construct(VendorRepository $vendorRepo)
    {
        $this->vendorRepository = $vendorRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vendor = $this->vendorRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );
        return $this->sendResponse($vendor->toArray(), 'Vendor retrieved successfully');
    }

    public function store(CreateVendorRequest $request)
    {
        //Create Vendor in the repository
        $vendor = $this->vendorRepository->createVendor($request);

        //Return the JSON version of the created Vendor.
        return $this->sendResponse($vendor->toArray(), 'Vendor saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vendor = $this->vendorRepository->find($id);
        if (empty($vendor)) {
            return $this->sendError('Vendor not found');
        }

        return $this->sendResponse($vendor->toArray(), 'Vendor Retrieved Successfully');
    }




    public function update($id, UpdateVendorRequest $request)
    {
        $data = $request->all();
        $vendor = $this->vendorRepository->find($id);

        if (empty($vendor)) {
            return $this->sendError('Vendor not found');
        }

        $vendor = $this->vendorRepository->update($data, $id);

        return $this->sendResponse($vendor->toArray(), 'Vendor Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vendor = $this->vendorRepository->find($id);

        if (empty($vendor)) {
            return $this->sendError('Vendor not found');
        }

        $vendor->delete();

        return $this->sendSuccess('Vendor Deleted Successfully');
    }
}
