<?php

namespace App\Http\Controllers;

use App\Helpers\LogHelper;
use App\Models\Contact;
use App\Models\TypeContact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        try {
            $ids = $request->query('id', null);
            $idCredential = $request->query('id_credential', null);
            
            $idParents = $request->query('id_parent', null);
            $route = $request->query('route', null);
            
            $idTypeContact = $request->query('id_type_contact', null);
            $value = $request->query('valeu', null);

            $active = $request->query('active', null);

            $perPage = $request->query('per_page', 10);
            $sortBy = $request->query('sort_by', 'id');
            $sortOrder = $request->query('sort_order', 'desc');

            $createdStart = $request->query('created_at_start', null);
            $createdEnd = $request->query('created_at_end', null);
            $updatedStart = $request->query('updated_at_start', null);
            $updatedEnd = $request->query('updated_at_end', null);

            $query = Contact::orderBy($sortBy, $sortOrder);

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

            if (!is_null($value)) {
                $query->where('value', 'LIKE', "%{$value}%");
                $appliedFilters['value'] = $value;
            }

            if (!is_null($idTypeContact)) {
                $query->where('id_type_contact', $idTypeContact);
                $appliedFilters['id_type_contact'] = $idTypeContact;
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

            $contact = $query->paginate($perPage);

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
                    'contact',
                    null,
                    $appliedFilters,
                    null,
                    $idPerson,
                    $idCredentialLog
                );
            }

            return response()->json([
                'contact' => $contact,
                'applied_filters' => $appliedFilters,
                'options' => [
                    'filters' => [
                        'id' => 'Filter by multiple IDs using comma-separated values',
                        'id_parent' => 'Record ID I am linking the record to',
                        'route' => 'Route name of the record',
                        'id_type_contact' => 'Filter by contact type ID, referencing type_contact table',
                        'value' => 'Filter by contact value (e.g., phone number, email, or other contact information)',

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
                'contact' => $contact, 
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

            $contact = Contact::where('id', $id)
                ->where('id_credential', $idCredential)
                ->first();

            if (!$contact) {
                return response()->json([
                    'error' => 'Not Found',
                    'details' => 'Contact not found'
                ], 404);
            }

            // Log da ação
            LogHelper::store(
                'show',
                'contact',
                $contact->id,
                null,
                null,
                $idPerson,
                $idCredential
            );

            return response()->json(['contact' => $contact], 200);

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
                'id_type_contact' => 'required|exists:type_contact,id', // Garante que o ID existe na tabela type_contact
                'value' => 'required|string', // O valor do contato deve ser uma string obrigatória
                'active' => 'sometimes|integer|in:0,1'
            ]);

            // Obtendo o tipo de contato e suas regras
            $typeContact = TypeContact::find($validatedData['id_type_contact']);

            // Validação específica para input_type "number"
            if ($typeContact->input_type === 'number') {
                // Remove caracteres especiais, deixando apenas números
                $validatedData['value'] = preg_replace('/[^0-9]/', '', $validatedData['value']);

                // Se após a limpeza o value não for numérico, rejeita a requisição
                if (!ctype_digit($validatedData['value'])) {
                    return response()->json(['error' => 'Invalid number format: only digits are allowed'], 422);
                }

                // Se houver máscara, validar se o número se encaixa em uma das opções
                if (!empty($typeContact->mask)) {
                    $validMasks = is_array($typeContact->mask) ? $typeContact->mask : [$typeContact->mask];
                    $valid = false;

                    foreach ($validMasks as $mask) {
                        // Remove caracteres especiais da máscara para comparação
                        $maskPattern = preg_replace('/[^0-9]/', '', $mask);
                        if (strlen($validatedData['value']) === strlen($maskPattern)) {
                            $valid = true;
                            break;
                        }
                    }

                    if (!$valid) {
                        return response()->json(['error' => 'Invalid number format: does not match the required mask'], 422);
                    }
                }
            }

            // Validação específica para input_type "email"
            if ($typeContact->input_type === 'email' && !filter_var($validatedData['value'], FILTER_VALIDATE_EMAIL)) {
                return response()->json(['error' => 'Invalid email format'], 422);
            }

            // Adiciona automaticamente o id_credential autenticado
            $validatedData['id_credential'] = $idCredential;

            $contact = Contact::create($validatedData);

            // Log da ação
            LogHelper::store(
                'created',
                'contact',
                $contact->id,
                null,
                $contact,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'Contact created successfully', 
                'contact' => $contact
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

            $contact = Contact::find($id);
            if (!$contact) {
                return response()->json([
                    'error' => 'Not Found', 
                    'details' => 'Contact not found'
                ], 404);
            }

            $validatedData = $request->validate([
                'id_parent' => 'required|integer',
                'route' => 'required|string',
                'id_type_contact' => 'required|exists:type_contact,id', // Garante que o ID existe na tabela type_contact
                'value' => 'required|string', // O valor do contato deve ser uma string obrigatória
                'active' => 'sometimes|integer|in:0,1'
            ]);

            // Obtendo o tipo de contato e suas regras
            $typeContact = TypeContact::find($validatedData['id_type_contact']);

            // Validação específica para input_type "number"
            if ($typeContact->input_type === 'number') {
                // Remove caracteres especiais, deixando apenas números
                $validatedData['value'] = preg_replace('/[^0-9]/', '', $validatedData['value']);

                // Se após a limpeza o value não for numérico, rejeita a requisição
                if (!ctype_digit($validatedData['value'])) {
                    return response()->json(['error' => 'Invalid number format: only digits are allowed'], 422);
                }

                // Se houver máscara, validar se o número se encaixa em uma das opções
                if (!empty($typeContact->mask)) {
                    $validMasks = is_array($typeContact->mask) ? $typeContact->mask : [$typeContact->mask];
                    $valid = false;

                    foreach ($validMasks as $mask) {
                        // Remove caracteres especiais da máscara para comparação
                        $maskPattern = preg_replace('/[^0-9]/', '', $mask);
                        if (strlen($validatedData['value']) === strlen($maskPattern)) {
                            $valid = true;
                            break;
                        }
                    }

                    if (!$valid) {
                        return response()->json(['error' => 'Invalid number format: does not match the required mask'], 422);
                    }
                }
            }

            // Validação específica para input_type "email"
            if ($typeContact->input_type === 'email' && !filter_var($validatedData['value'], FILTER_VALIDATE_EMAIL)) {
                return response()->json(['error' => 'Invalid email format'], 422);
            }
            
            $oldData = $contact->toArray();
            $contact->update($validatedData);

            // Log da ação
            LogHelper::store(
                'updated',
                'contact',
                $contact->id,
                $oldData,
                $contact,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'Contact updated successfully', 
                'contact' => $contact
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

            $contact = Contact::find($id);
            if (!$contact) {
                return response()->json([
                    'error' => 'Not Found', 
                    'details' => 'Contact not found'
                ], 404);
            }

            $oldData = $contact->toArray();
            $contact->delete();

            // Log da ação
            LogHelper::store(
                'deleted',
                'contact',
                $id,
                $oldData,
                null,
                $idPerson,
                $idCredential
            );

            return response()->json([
                'message' => 'Contact deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error', 
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
