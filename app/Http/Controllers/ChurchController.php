<?php

namespace App\Http\Controllers;

use App\Models\Church;
use Illuminate\Http\Request;

class ChurchController extends Controller
{
    public function index(Request $request)
    {
        try {
            $ids = $request->query('id', null);
            $idCredential = $request->query('id_credential', null);
            $name = $request->query('name', null);
            $active = $request->query('active', null);

            $perPage = $request->query('per_page', 10);
            $sortBy = $request->query('sort_by', 'id');
            $sortOrder = $request->query('sort_order', 'desc');

            $createdStart = $request->query('created_at_start', null);
            $createdEnd = $request->query('created_at_end', null);
            $updatedStart = $request->query('updated_at_start', null);
            $updatedEnd = $request->query('updated_at_end', null);

            $query = Church::orderBy($sortBy, $sortOrder);

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

            $churchs = $query->paginate($perPage);

            return response()->json([
                'churchs' => $churchs,
                'applied_filters' => $appliedFilters,
                'options' => [
                    'filters' => [
                        'id' => 'Filter by multiple IDs using comma-separated values',
                        'name' => 'Filter by gender name using LIKE',
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
                'churchs' => $churchs, 
                'applied_filters' => $appliedFilters
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
                'name' => 'required|string|unique:church,name', 
                'active' => 'sometimes|integer|in:0,1'
            ]);
            
            $validatedData['id_credential'] = $idCredential;

            $church = Church::create($validatedData);

            return response()->json([
                'message' => 'Church created successfully', 
                'church' => $church
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

    public function show($id)
    {
        try {
            $church = Church::find($id);
            if (!$church) {
                return response()->json([
                    'error' => 'Not Found', 
                    'details' => 'Church not found'
                ], 404);
            }
            return response()->json([
                'church' => $church
            ], 200);

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
            $church = Church::find($id);
            if (!$church) {
                return response()->json([
                    'error' => 'Not Found', 
                    'details' => 'Church not found'
                ], 404);
            }

            $validatedData = $request->validate([
                'name' => 'sometimes|string|unique:church,name,' . $id, 
                'active' => 'sometimes|integer|in:0,1'
            ]);

            $church->update($validatedData);

            return response()->json([
                'message' => 'Church updated successfully', 
                'church' => $church
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

    public function destroy($id)
    {
        try {
            $church = Church::find($id);
            if (!$church) {
                return response()->json([
                    'error' => 'Not Found', 
                    'details' => 'Church not found'
                ], 404);
            }

            $church->delete();

            return response()->json([
                'message' => 'Church deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error', 
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
