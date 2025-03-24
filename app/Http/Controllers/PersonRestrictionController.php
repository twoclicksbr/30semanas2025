<?php

namespace App\Http\Controllers;

use App\Models\PersonRestriction;
use Illuminate\Http\Request;

class PersonRestrictionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $ids = $request->query('id', null);
            $idCredential = $request->query('id_credential', null);
            $idPerson = $request->query('id_person', null);
            $idTypeUser = $request->query('id_type_user', null);
            $active = $request->query('active', null);

            $perPage = $request->query('per_page', 10);
            $sortBy = $request->query('sort_by', 'id');
            $sortOrder = $request->query('sort_order', 'desc');

            $createdStart = $request->query('created_at_start', null);
            $createdEnd = $request->query('created_at_end', null);
            $updatedStart = $request->query('updated_at_start', null);
            $updatedEnd = $request->query('updated_at_end', null);

            $query = PersonRestriction::orderBy($sortBy, $sortOrder);

            $appliedFilters = [
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
                'per_page' => $perPage,
                'page' => $request->query('page', 1),
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

            if (!is_null($idPerson)) {
                $query->where('id_person', $idPerson);
                $appliedFilters['id_person'] = $idPerson;
            }

            if (!is_null($idTypeUser)) {
                $query->where('id_type_user', $idTypeUser);
                $appliedFilters['id_type_user'] = $idTypeUser;
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

            $restrictions = $query->paginate($perPage);

            return response()->json([
                'restrictions' => $restrictions,
                'applied_filters' => $appliedFilters,
                'options' => [
                    'filters' => [
                        'id' => 'Filter by multiple IDs using comma-separated values',
                        'id_person' => 'Filter by person ID',
                        'id_type_user' => 'Filter by type user ID',
                        'id_credential' => 'Filter by credential ID',
                        'active' => 'Filter by status (0 = inactive, 1 = active)',
                        'created_at_start' => 'Filter records created from this date (Y-m-d H:i:s)',
                        'created_at_end' => 'Filter records created until this date (Y-m-d H:i:s)',
                        'updated_at_start' => 'Filter records updated from this date (Y-m-d H:i:s)',
                        'updated_at_end' => 'Filter records updated until this date (Y-m-d H:i:s)',
                    ],
                    'sorting' => [
                        'sort_by' => 'Sort by id, id_person, id_type_user, active, created_at, updated_at',
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

    public function show($id)
    {
        try {
            $restriction = PersonRestriction::find($id);

            if (!$restriction) {
                return response()->json([
                    'error' => 'Not Found',
                    'details' => 'PersonRestriction not found'
                ], 404);
            }

            return response()->json([
                'person_restriction' => $restriction
            ], 200);

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
            $idCredential = session('id_credential');

            if (!$idCredential) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'details' => 'Invalid session. Please authenticate again.'
                ], 401);
            }

            $validatedData = $request->validate([
                'id_person' => 'required|exists:person,id',
                'id_type_user' => 'required|exists:type_user,id',
                'active' => 'sometimes|integer|in:0,1',
            ]);

            $validatedData['id_credential'] = $idCredential;

            $restriction = PersonRestriction::create($validatedData);

            return response()->json([
                'message' => 'PersonRestriction created successfully',
                'person_restriction' => $restriction,
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




}
