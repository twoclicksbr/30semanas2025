@extends('layouts.app')

@section('title', 'Tipo de Participação')

@section('content')
    <section class="wrapper image-wrapper bg-image bg-overlay bg-overlay-light-100 text-white"
        data-image-src="https://30semanas.com.br/assets/img/photos/bg26.jpg">
        <div class="container pt-17 pb-20 pt-md-19 pb-md-21 text-center">
            <div class="row">
                <div class="col-lg-8 mx-auto"></div>
            </div>
        </div>
    </section>

    <section class="wrapper mb-10">
        <div class="container pb-14 pb-md-16">
            <div class="row">
                <div class="col-lg-7 col-xl-6 col-xxl-12 mx-auto mt-n20">
                    <div class="card">
                        <div class="card-body p-11">
                            <h2 class="mb-3 text-start">Tipo de Participação</h2>

                            <div class="d-flex gap-2 mb-4">
                                <button class="btn btn-sm btn-soft-orange btn-icon btn-icon-start rounded"
                                    onclick="openCreateModal()">
                                    <i class="uil uil-plus"></i> Novo
                                </button>

                                <button class="btn btn-sm btn-soft-orange btn-icon btn-icon-start rounded"
                                    onclick="openSearchModal()">
                                    <i class="uil uil-credit-card-search"></i> Pesquisa
                                </button>

                                <div id="clear-filters-wrapper" class="d-none">
                                    <button class="btn btn-sm btn-soft-ash btn-icon btn-icon-start rounded" onclick="clearSearchFilters()">
                                        <i class="uil uil-times-circle"></i> Limpar Filtros
                                    </button>
                                </div>

                                {{-- <a class="btn btn-sm btn-soft-orange btn-icon btn-icon-start rounded" href="#">
                                    <i class="uil uil-credit-card-search"></i> Pesquisa
                                </a> --}}
                            </div>

                            <div class="table-responsive">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="text-end">
                                        <label for="perPageSelect" class="form-label me-2 mb-0">Registros por
                                            página:</label>
                                        <select id="perPageSelect" class="form-select form-select-sm d-inline-block w-auto"
                                            onchange="changePerPage()">
                                            <option value="5">5</option>
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>

                                    <p class="lead mb-0 text-start" style="font-size: 15px" id="record-info">
                                        Mostrando 0 registros de 0 no total.
                                    </p>

                                </div>

                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col" nowrap width="10px" style="cursor: pointer;"
                                                onclick="setSort('id')" data-sort="id">
                                                Id:
                                                <span id="sort-icon-id">
                                                    <i class="uil uil-arrows-v-alt" style="vertical-align: middle;"></i>
                                                </span>
                                            </th>
                                            <th scope="col" nowrap style="cursor: pointer;" onclick="setSort('name')"
                                                data-sort="name">
                                                Nome:
                                                <span id="sort-icon-name">
                                                    <i class="uil uil-arrows-v-alt" style="vertical-align: middle;"></i>
                                                </span>
                                            </th>
                                            <th scope="col" nowrap width="20px" style="cursor: pointer;"
                                                onclick="setSort('active')" data-sort="active">
                                                Ativo:
                                                <span id="sort-icon-active">
                                                    <i class="uil uil-arrows-v-alt" style="vertical-align: middle;"></i>
                                                </span>
                                            </th>
                                            <th scope="col" nowrap width="10px">Ações:</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data-table"></tbody>
                                </table>

                                <nav id="pagination-wrapper" class="mt-4 d-flex justify-content-end">
                                    <ul class="pagination"></ul>
                                </nav>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal de Pesquisa -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-5">
                    <h5 class="modal-title">Pesquisar Registros</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0 pb-0">
                    <form id="search-form" onsubmit="submitSearch(); return false;" class="mb-5 row g-3 px-3">

                        <div class="col-md-2">
                            <label for="search_id" class="form-label">ID</label>
                            <input type="text" class="form-control" id="search_id" placeholder="Ex: 1,2,3">
                        </div>

                        <div class="col-md-8">
                            <label for="search_name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="search_name" placeholder="Nome">
                        </div>

                        <div class="col-md-2">
                            <label for="search_active" class="form-label">Status</label>
                            <select id="search_active" class="form-select">
                                <option value="">Todos</option>
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Criado entre</label>
                            <div class="d-flex gap-2">
                                <input type="datetime-local" class="form-control" id="search_created_start">
                                <input type="datetime-local" class="form-control" id="search_created_end">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Atualizado entre</label>
                            <div class="d-flex gap-2">
                                <input type="datetime-local" class="form-control" id="search_updated_start">
                                <input type="datetime-local" class="form-control" id="search_updated_end">
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-soft-orange" data-bs-dismiss="modal">
                                <i class="uil uil-times me-1"></i> Cancelar
                            </button>

                            <button type="submit" class="btn btn-orange">
                                <i class="uil uil-search me-1"></i> Confirmar
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal Novo/Editar Registro -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-5">
                    <h5 class="modal-title" id="modalTitle">Novo Registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0 pb-0">
                    <form id="create-form" onsubmit="submitCreate(); return false;" class="mb-5">
                        <input type="hidden" id="record_id">

                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nome"
                                required>
                            <label for="name">Nome</label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center px-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                                <label class="form-check-label" for="active">Participante Ativo</label>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-soft-orange" data-bs-dismiss="modal">
                                    <i class="uil uil-corner-up-left-alt me-1"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-orange btn-login">
                                    <i class="uil uil-save me-1"></i> Gravar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header p-5">
                    <h5 class="modal-title">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0 pb-0">
                    <p class="text-center">Você está prestes a excluir este registro. Deseja continuar?</p>
                    <div class="form-check text-start">
                        <input class="form-check-input" type="checkbox" id="confirmDeleteCheck">
                        <label class="form-check-label" for="confirmDeleteCheck">
                            Confirma que deseja excluir?
                        </label>
                    </div>
                </div>

                <div class="modal-footer p-2">
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <button type="button" class="btn btn-soft-orange" data-bs-dismiss="modal">
                                <i class="uil uil-corner-up-left-alt" style="margin-right: 10px"></i> Cancelar
                            </button>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <button type="button" class="btn btn-orange btn-login w-100 mb-2" onclick="confirmDelete()"
                                id="confirmDeleteBtn" disabled><i class="uil uil-trash-alt"
                                    style="margin-right: 10px"></i>Excluir</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const API_URL = `{{ config('api.base_url') }}/api/v1/type_participation`;
        const username = `{{ config('api.username') }}`;
        const token = `{{ config('api.token') }}`;
        let deleteId = null;

        let sortBy = null;
        let sortOrder = null;

        let searchFilters = {};

        let currentPage = null;
        let lastPage = null;
        let perPage = null;

        async function loadData() {
            try {
                const params = {};
                if (sortBy && sortOrder) {
                    params.sort_by = sortBy;
                    params.sort_order = sortOrder;
                }
                params.page = currentPage;
                params.per_page = perPage;

                // Adiciona os filtros de busca se existirem
                Object.keys(searchFilters).forEach(key => {
                    if (searchFilters[key]) {
                        params[key] = searchFilters[key];
                    }
                });

                const res = await axios.get(API_URL, {
                    headers: {
                        username,
                        token
                    },
                    params
                });

                const typeParticipations = res.data.type_participations;
                const data = typeParticipations?.data || [];

                currentPage = typeParticipations.current_page;
                lastPage = typeParticipations.last_page;
                perPage = typeParticipations.per_page;


                // SOMENTE atualiza os filtros se não houver interação do usuário
                if (!sortBy && !sortOrder && res.data.applied_filters) {
                    sortBy = res.data.applied_filters.sort_by || null;
                    sortOrder = res.data.applied_filters.sort_order || null;
                }

                updateSortIcons();

                let rows = '';
                data.forEach(item => {
                    const statusIcon = item.active ?
                        '<i class="text-green uil uil-thumbs-up"></i>' :
                        '<i class="text-red uil uil-thumbs-down"></i>';

                    rows += `
                        <tr ondblclick='openEditModal(${JSON.stringify(item)})'>
                            <td>${item.id}</td>
                            <td>${item.name}</td>
                            <td class="text-center">${statusIcon}</td>
                            <td>
                                <a href="#" class="text-yellow me-2" onclick='openEditModal(${JSON.stringify(item)})'>
                                    <i class="uil uil-edit"></i>
                                </a>
                                <a href="#" class="text-danger" onclick='openDeleteModal(${item.id})'>
                                    <i class="uil uil-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                });

                document.getElementById('data-table').innerHTML = rows;
                document.getElementById('record-info').innerText =
                    `${typeParticipations.to - typeParticipations.from + 1} registros exibidos, de um total de ${typeParticipations.total}`;

                renderPagination(typeParticipations.links);
                document.getElementById('perPageSelect').value = perPage;

                const hasFilters = Object.values(searchFilters).some(v => v);
                document.getElementById('clear-filters-wrapper').classList.toggle('d-none', !hasFilters);


            } catch (err) {
                console.error('Erro ao carregar dados:', err);
                alert('Erro ao carregar dados');
            }
        }

        function changePerPage() {
            perPage = parseInt(document.getElementById('perPageSelect').value);
            currentPage = 1;
            loadData();
        }


        function setSort(column) {
            if (sortBy === column) {
                sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                sortBy = column;
                sortOrder = 'asc';
            }
            loadData();
        }

        function updateSortIcons() {
            ['id', 'name', 'active'].forEach(col => {
                const th = document.querySelector(`[data-sort="${col}"]`);
                const icon = document.getElementById(`sort-icon-${col}`);

                if (th) {
                    th.classList.toggle('text-orange', sortBy === col);
                }

                if (icon) {
                    if (sortBy === col) {
                        icon.innerHTML = sortOrder === 'asc' ?
                            '<i class="uil uil-arrow-up" style="vertical-align: middle;"></i>' :
                            '<i class="uil uil-arrow-down" style="vertical-align: middle;"></i>';
                    } else {
                        icon.innerHTML = '<i class="uil uil-arrows-v-alt" style="vertical-align: middle;"></i>';
                    }
                }
            });
        }

        function renderPagination(links) {
            const pagination = document.querySelector('#pagination-wrapper .pagination');
            pagination.innerHTML = '';

            links.forEach(link => {
                // Ignora Previous e Next
                if (link.label.includes('Previous') || link.label.includes('Next')) return;

                const li = document.createElement('li');
                li.classList.add('page-item');
                if (link.active) li.classList.add('active');
                if (!link.url) li.classList.add('disabled');

                const a = document.createElement('a');
                a.classList.add('page-link');
                a.innerHTML = link.label;
                a.href = '#';

                if (link.url) {
                    a.addEventListener('click', e => {
                        e.preventDefault();
                        const url = new URL(link.url);
                        currentPage = parseInt(url.searchParams.get('page'));
                        loadData();
                    });
                }

                li.appendChild(a);
                pagination.appendChild(li);
            });

        }

        function openCreateModal() {
            document.getElementById('create-form').reset();
            document.getElementById('record_id').value = '';
            document.getElementById('modalTitle').innerText = 'Novo Registro';
            document.getElementById('active').checked = true;
            new bootstrap.Modal(document.getElementById('createModal')).show();
            setTimeout(() => document.getElementById('name').focus(), 300);
        }

        function openSearchModal() {
            const form = document.getElementById('search-form');

            // Preenche os campos com os filtros ativos
            form.search_id.value = searchFilters.id || '';
            form.search_name.value = searchFilters.name || '';
            form.search_active.value = searchFilters.active || '';
            form.search_created_start.value = convertToInputDateTime(searchFilters.created_at_start);
            form.search_created_end.value = convertToInputDateTime(searchFilters.created_at_end);
            form.search_updated_start.value = convertToInputDateTime(searchFilters.updated_at_start);
            form.search_updated_end.value = convertToInputDateTime(searchFilters.updated_at_end);

            new bootstrap.Modal(document.getElementById('searchModal')).show();
        }

        function convertToInputDateTime(value) {
            if (!value) return '';
            const date = new Date(value.replace(' ', 'T'));
            return date.toISOString().slice(0, 16);
        }


        function submitSearch() {
            const form = document.getElementById('search-form');

            // Armazena os valores digitados
            searchFilters = {
                id: form.search_id.value,
                name: form.search_name.value,
                active: form.search_active.value,
                created_at_start: formatDateTime(form.search_created_start.value),
                created_at_end: formatDateTime(form.search_created_end.value),
                updated_at_start: formatDateTime(form.search_updated_start.value),
                updated_at_end: formatDateTime(form.search_updated_end.value)
            };

            currentPage = 1; // volta para a primeira página
            bootstrap.Modal.getInstance(document.getElementById('searchModal')).hide(); // fecha o modal
            loadData(); // recarrega os dados com os filtros
        }

        function formatDateTime(value) {
            if (!value) return '';
            return new Date(value).toISOString().slice(0, 19).replace('T', ' ');
        }

        function clearSearchFilters() {
            searchFilters = {};
            currentPage = 1;
            loadData();
            const modalEl = document.getElementById('searchModal');
            if (bootstrap.Modal.getInstance(modalEl)) {
                bootstrap.Modal.getInstance(modalEl).hide();
            }
        }

        function openEditModal(item) {
            document.getElementById('record_id').value = item.id;
            document.getElementById('name').value = item.name;
            document.getElementById('active').checked = item.active == 1;
            document.getElementById('modalTitle').innerText = 'Editar Registro';
            new bootstrap.Modal(document.getElementById('createModal')).show();
            setTimeout(() => document.getElementById('name').focus(), 300);
        }

        function submitCreate() {
            const form = document.getElementById('create-form');
            const id = document.getElementById('record_id').value;
            const payload = {
                name: form.name.value,
                active: form.active.checked ? 1 : 0
            };

            const url = id ? `${API_URL}/${id}` : API_URL;
            const method = id ? 'put' : 'post';

            axios({
                    method,
                    url,
                    data: payload,
                    headers: {
                        username,
                        token
                    }
                })
                .then(() => {
                    bootstrap.Modal.getInstance(document.getElementById('createModal')).hide();
                    loadData();
                })
                .catch(() => alert('Erro ao salvar'));
        }

        function openDeleteModal(id) {
            deleteId = id;
            document.getElementById('confirmDeleteCheck').checked = false;
            document.getElementById('confirmDeleteBtn').disabled = true;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        document.getElementById('confirmDeleteCheck').addEventListener('change', function() {
            document.getElementById('confirmDeleteBtn').disabled = !this.checked;
        });

        function confirmDelete() {
            if (!deleteId) return;
            axios.delete(`${API_URL}/${deleteId}`, {
                    headers: {
                        username,
                        token
                    }
                })
                .then(() => {
                    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                    loadData();
                })
                .catch(() => alert('Erro ao excluir'));
        }

        loadData();
    </script>
@endsection
