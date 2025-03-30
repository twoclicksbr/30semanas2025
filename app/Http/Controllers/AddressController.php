<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        try {
            $ids = $request->query('id', null);
            $idCredential = $request->query('id_credential', null);
            
            $idParents = $request->query('id_parent', null);
            $route = $request->query('route', null);
            
            $cep = $request->query('cep', null);
            $logradouro = $request->query('logradouro', null);
            $numero = $request->query('numero', null);
            $complemento = $request->query('complemento', null);
            $bairro = $request->query('bairro', null);
            $localidade = $request->query('localidade', null);
            $uf = $request->query('uf', null);

            $active = $request->query('active', null);

            $perPage = $request->query('per_page', 10);
            $sortBy = $request->query('sort_by', 'id');
            $sortOrder = $request->query('sort_order', 'desc');

            $createdStart = $request->query('created_at_start', null);
            $createdEnd = $request->query('created_at_end', null);
            $updatedStart = $request->query('updated_at_start', null);
            $updatedEnd = $request->query('updated_at_end', null);

            $query = Address::orderBy($sortBy, $sortOrder);

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

            if (!is_null($idParents)) {
                $idParentArray = explode(',', $idParents);
                $query->whereIn('id_parent', $idParentArray);
                $appliedFilters['id_parent'] = $idParentArray;
            }

            if (!is_null($route)) {
                $query->where('route', 'LIKE', "%{$route}%");
                $appliedFilters['route'] = $route;
            }

            if (!is_null($cep)) {
                $query->where('cep', $cep);
                $appliedFilters['cep'] = $cep;
            }

            if (!is_null($logradouro)) {
                $query->where('logradouro', 'LIKE', "%{$logradouro}%");
                $appliedFilters['logradouro'] = $logradouro;
            }

            if (!is_null($numero)) {
                $query->where('numero', 'LIKE', "%{$numero}%");
                $appliedFilters['numero'] = $numero;
            }

            if (!is_null($complemento)) {
                $query->where('complemento', 'LIKE', "%{$complemento}%");
                $appliedFilters['complemento'] = $complemento;
            }

            if (!is_null($bairro)) {
                $query->where('bairro', 'LIKE', "%{$bairro}%");
                $appliedFilters['bairro'] = $bairro;
            }

            if (!is_null($localidade)) {
                $query->where('localidade', 'LIKE', "%{$localidade}%");
                $appliedFilters['localidade'] = $localidade;
            }

            if (!is_null($uf)) {
                $query->where('uf', $uf);
                $appliedFilters['uf'] = $uf;
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

            if (!is_null($active)) {
                if (!in_array($active, ['0', '1'], true)) {
                    return response()->json([
                        'error' => 'Invalid active parameter',
                        'details' => 'Allowed values: 0 (inactive), 1 (active)'
                    ], 400);
                }
                $query->where('active', $active);
                $appliedFilters['active'] = $active;
            }

            $address = $query->paginate($perPage);

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
                    'address',
                    null,
                    $appliedFilters,
                    null,
                    $idPerson,
                    $idCredentialLog
                );
            }

            return response()->json([
                'address' => $address,
                'applied_filters' => $appliedFilters,
                'options' => [
                    'filters' => [
                        'id' => 'Filter by multiple IDs using comma-separated values',

                        'id_parent' => 'Record ID I am linking the record to',
                        'route' => 'Route name of the record',

                        'cep' => 'Enter the zip code',
                        'logradouro' => 'Enter the street address',
                        'numero' => 'Enter the number',
                        'complemento' => 'Enter the complement',
                        'bairro' => 'Enter the neighborhood',
                        'localidade' => 'Enter the location',
                        'uf' => 'Enter the State',

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

            return response()->json([
                'address' => $address, 
                'applied_filters' => $appliedFilters
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

            $address = Address::where('id', $id)
                ->where('id_credential', $idCredential)
                ->first();

            if (!$address) {
                return response()->json([
                    'error' => 'Not Found',
                    'details' => 'Address not found'
                ], 404);
            }

            // Log da ação
            LogHelper::store(
                'show',
                'address',
                $address->id,
                null,
                null,
                $idPerson,
                $idCredential
            );

            return response()->json(['address' => $address], 200);

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

                'id_parent' => 'required|integer',
                'route' => 'required|string',

                'cep' => 'required|string',
                'logradouro' => 'required|string',
                'numero' => 'required|string',
                'complemento' => 'nullable|string',
                'bairro' => 'required|string',
                'localidade' => 'required|string',
                'uf' => 'required|string|size:2',

                'active' => 'sometimes|integer|in:0,1'
            ]);

            // Remover caracteres não numéricos do CEP
            $validatedData['cep'] = preg_replace('/\D/', '', $validatedData['cep']);
            
            $validatedData['id_credential'] = $idCredential;

            $address = Address::create($validatedData);

            // Log da ação
            LogHelper::store(
                'created',
                'address',
                $address->id,
                null,
                $address,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'Address created successfully', 
                'address' => $address
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

            $address = Address::find($id);
            if (!$address) {
                return response()->json([
                    'error' => 'Not Found', 
                    'details' => 'Address not found'
                ], 404);
            }

            $validatedData = $request->validate([
                
                'id_parent' => 'required|integer',
                'route' => 'required|string',

                'cep' => 'required|string',
                'logradouro' => 'required|string',
                'numero' => 'required|string',
                'complemento' => 'nullable|string',
                'bairro' => 'required|string',
                'localidade' => 'required|string',
                'uf' => 'required|string|size:2',

                'active' => 'sometimes|integer|in:0,1'
            ]);

            // Remover caracteres não numéricos do CEP
            $validatedData['cep'] = preg_replace('/\D/', '', $validatedData['cep']);

            $oldData = $address->toArray();
            $address->update($validatedData);

            // Log da ação
            LogHelper::store(
                'updated',
                'address',
                $address->id,
                $oldData,
                $address,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'Address updated successfully', 
                'address' => $address
            ], 200);

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

            $address = Address::find($id);
            if (!$address) {
                return response()->json([
                    'error' => 'Not Found', 
                    'details' => 'Address not found'
                ], 404);
            }

            $oldData = $address->toArray();
            $address->delete();

            // Log da ação
            LogHelper::store(
                'deleted',
                'address',
                $id,
                $oldData,
                null,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'Address deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error', 
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
