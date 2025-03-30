<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\TypeContact;
use Illuminate\Http\Request;

class TypeContactController extends Controller
{
    public function index(Request $request)
    {
        try {
            $ids = $request->query('id', null);
            $idCredential = $request->query('id_credential', null);
            
            $name = $request->query('name', null);
            $inputType = $request->query('input_type', null);
            $active = $request->query('active', null);
            
            $perPage = $request->query('per_page', 10);
            $sortBy = $request->query('sort_by', 'id');
            $sortOrder = $request->query('sort_order', 'desc');
            
            $createdStart = $request->query('created_at_start', null);
            $createdEnd = $request->query('created_at_end', null);
            $updatedStart = $request->query('updated_at_start', null);
            $updatedEnd = $request->query('updated_at_end', null);

            $query = TypeContact::orderBy($sortBy, $sortOrder);
            
            $appliedFilters = [
                'sort_by' => $sortBy, 
                'sort_order' => $sortOrder, 
                'per_page' => $perPage, 
                'page' => $request->query('page', 1)
            ];

            if (!is_null($ids)) {
                $idArray = explode(',', $ids);
                $query->whereIn('id', $idArray);
                $appliedFilters['id'] = $idArray;
            }

            if (!is_null($idCredential)) {
                $query->where('id_credential', $idCredential);
                $appliedFilters['id_credential'] = $idCredential;
            }

            if (!is_null($name)) {
                $query->where('name', 'LIKE', "%{$name}%");
                $appliedFilters['name'] = $name;
            }

            if (!is_null($inputType)) {
                $query->where('input_type', 'LIKE', "%{$inputType}%");
                $appliedFilters['input_type'] = $inputType;
            }

            if (!is_null($createdStart)) {
                $query->where('created_at', '>=', $createdStart);
                $appliedFilters['created_at_start'] = $createdStart;
            }
            if (!is_null($createdEnd)) {
                $query->where('created_at', '<=', $createdEnd);
                $appliedFilters['created_at_end'] = $createdEnd;
            }

            if (!is_null($updatedStart)) {
                $query->where('updated_at', '>=', $updatedStart);
                $appliedFilters['updated_at_start'] = $updatedStart;
            }
            if (!is_null($updatedEnd)) {
                $query->where('updated_at', '<=', $updatedEnd);
                $appliedFilters['updated_at_end'] = $updatedEnd;
            }

            if (!is_null($active) && in_array($active, ['0', '1'], true)) {
                $query->where('active', $active);
                $appliedFilters['active'] = $active;
            }

            $typeContacts = $query->paginate($perPage);

            $idPerson = $request->header('id_person');
            if (!$idPerson) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Missing id_person in headers'
                ], 401);
            }

            $idCredentialLog = session('id_credential');

            if ($idPerson && $idCredentialLog) {
                LogHelper::store(
                    'viewed',
                    'type_contact',
                    null,
                    $appliedFilters,
                    null,
                    $idPerson,
                    $idCredentialLog
                );
            }

            return response()->json([
                'typeContacts' => $typeContacts, 
                'applied_filters' => $appliedFilters,
                'options' => [
                    'filters' => [
                        'id' => 'Filter by multiple IDs using comma-separated values',
                        'name' => 'Filter by type contact name using LIKE',
                        'input_type' => 'Filter by input type (number, email, text)',
                        'active' => 'Filter by status (0 = inactive, 1 = active)',
                        
                        'created_at_start' => 'Filter records created from this date (Y-m-d H:i:s)',
                        'created_at_end' => 'Filter records created until this date (Y-m-d H:i:s)',
                        'updated_at_start' => 'Filter records updated from this date (Y-m-d H:i:s)',
                        'updated_at_end' => 'Filter records updated until this date (Y-m-d H:i:s)',
                    ],
                    'sorting' => [
                        'sort_by' => 'Sort by id, name, active, created_at, updated_at',
                        'sort_order' => 'Sorting order: asc (ascending) or desc (descending, default)',
                    ],
                    'pagination' => [
                        'per_page' => 'Number of records per page (default: 10)',
                        'page' => 'Specify the page number',
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error', 
                'details' => $e->getMessage()
            ], 500);

        }
    }

    public function show(Request $request, $id)
    {
        try {
            $idPerson = $request->header('id_person');
            $idCredential = session('id_credential');

            if (!$idPerson || !$idCredential) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Missing id_person or invalid session'
                ], 401);
            }

            $typeContact = TypeContact::where('id', $id)
                ->where('id_credential', $idCredential)
                ->first();

            if (!$typeContact) {
                return response()->json([
                    'error' => 'Not Found',
                    'details' => 'TypeContact not found'
                ], 404);
            }

            // Log da ação
            LogHelper::store(
                'show',
                'type_contact',
                $typeContact->id,
                null,
                null,
                $idPerson,
                $idCredential
            );

            return response()->json(['type_contact' => $typeContact], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $idPerson = $request->header('id_person');
            if (!$idPerson) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Missing id_person in headers'
                ], 401);
            }
            
            $idCredential = session('id_credential');
            if (!$idCredential) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Invalid session. Please authenticate again.'
                ], 401);
            }

            $validatedData = $request->validate([
                'name' => 'required|string|unique:type_contact,name',
                'input_type' => 'required|string|in:number,email,text',
                'mask' => 'nullable|string', // Aceita string separada por vírgulas
                'active' => 'sometimes|integer|in:0,1'
            ]);

            // Se a máscara for uma string com múltiplos valores separados por vírgula, converter para array
            if (isset($validatedData['mask']) && is_string($validatedData['mask'])) {
                $validatedData['mask'] = array_map('trim', explode(',', $validatedData['mask']));
            }

            $validatedData['id_credential'] = $idCredential;

            $typeContact = TypeContact::create($validatedData);

            // Log da ação
            LogHelper::store(
                'created',
                'typeContact',
                $typeContact->id,
                null,
                $typeContact,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'TypeContact created successfully', 
                'typeContact' => $typeContact
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation error', 
                'fields' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error', 
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $idPerson = $request->header('id_person');
            if (!$idPerson) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Missing id_person in headers'
                ], 401);
            }

            $idCredential = session('id_credential');
            if (!$idCredential) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Invalid session. Please authenticate again.'
                ], 401);
            }

            $typeContact = TypeContact::find($id);
            if (!$typeContact) {
                return response()->json(['error' => 'Not Found', 'details' => 'TypeContact not found'], 404);
            }

            $validatedData = $request->validate([
                'name' => 'sometimes|string|unique:type_contact,name,' . $id,
                'input_type' => 'sometimes|string|in:number,email,text',
                'mask' => 'nullable|string', // Aceita string separada por vírgulas
                'active' => 'sometimes|integer|in:0,1'
            ]);

            // Se a máscara for uma string com múltiplos valores separados por vírgula, converter para array
            if (isset($validatedData['mask']) && is_string($validatedData['mask'])) {
                $validatedData['mask'] = array_map('trim', explode(',', $validatedData['mask']));
            }

            $oldData = $typeContact->toArray();
            $typeContact->update($validatedData);

            // Log da ação
            LogHelper::store(
                'updated',
                'type_contact',
                $typeContact->id,
                $oldData,
                $typeContact,
                $idPerson,
                $idCredential
            );

            return response()->json(['message' => 'TypeContact updated successfully', 'typeContact' => $typeContact], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation error', 'fields' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $idPerson = $request->header('id_person');
            if (!$idPerson) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Missing id_person in headers'
                ], 401);
            }

            $idCredential = session('id_credential');
            if (!$idCredential) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Invalid session. Please authenticate again.'
                ], 401);
            }

            $typeContact = TypeContact::find($id);
            if (!$typeContact) {
                return response()->json([
                    'error' => 'Not Found', 
                    'details' => 'TypeContact not found'
                ], 404);
            }

            $oldData = $typeContact->toArray();
            $typeContact->delete();

            // Log da ação
            LogHelper::store(
                'deleted',
                'type_contact',
                $id,
                $oldData,
                null,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'TypeContact deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error', 
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
