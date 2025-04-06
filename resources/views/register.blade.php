@extends('layouts.app')

@section('title', 'Inscreva-se')

@section('content')

    @include('partials.painel')

    <section class="wrapper mb-10">
        <div class="container pb-14 pb-md-16">
            <div class="row">
                <div class="col-lg-7 col-xl-6 col-xxl-10 mx-auto mt-n20">
                    <div class="card">
                        <div class="card-body p-11">
                            <h2 class="mb-3 text-start">Inscreva-se</h2>

                            <div id="formErrors" class="alert alert-danger d-none"></div>

                            <form id="multiStepForm">
                                @csrf

                                <!-- Etapa 1 -->
                                <div class="step active" id="step1">
                                    <p class="lead mb-6 text-start">1. Dados Pessoais</p>
                                    <div class="row mb-2">
                                        <div class="col-lg-8">
                                            <input class="form-control" id="name" placeholder="Nome" required>
                                        </div>
                                        <div class="col-lg-4">
                                            <select class="form-select" id="id_gender" required>
                                                <option value="">Carregando...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-6">
                                            <input class="form-control" type="text" id="cpf" name="cpf"
                                                placeholder="CPF" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <input class="form-control" type="date" id="dt_nascimento" required>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-6">
                                            <input class="form-control" type="text" id="whatsapp" placeholder="WhatsApp"
                                                required>
                                        </div>
                                        <div class="col-lg-6">
                                            <input class="form-control" type="text" id="eklesia" placeholder="Eklesia"
                                                required>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-orange w-100 mb-2"
                                        onclick="nextStep(1)">Próximo</button>
                                </div>

                                <!-- Etapa 2 -->
                                <div class="step" id="step2">
                                    <p class="lead mb-6 text-start">2. Criar Usuário</p>
                                    <div class="row mb-2">
                                        <div class="col-lg-8">
                                            <input class="form-control" type="email" id="email_user" name="email_user"
                                                placeholder="E-mail" required>
                                        </div>
                                        <div class="col-lg-4">
                                            <input class="form-control" type="password" id="password" placeholder="Senha"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-6">
                                            <button class="btn btn-soft-orange w-100 mb-2" type="button"
                                                onclick="prevStep(2)">Voltar</button>
                                        </div>
                                        <div class="col-lg-6">
                                            <button class="btn btn-orange w-100 mb-2" type="button"
                                                onclick="nextStep(2)">Próximo</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Etapa 3 -->
                                <div class="step" id="step3">
                                    <p class="lead mb-6 text-start">3. Endereço</p>
                                    <div class="row mb-2">
                                        <div class="col-lg-3">
                                            <input class="form-control" type="text" id="cep" placeholder="CEP"
                                                required>
                                        </div>
                                        <div class="col-lg-7">
                                            <input class="form-control" type="text" id="logradouro"
                                                placeholder="Logradouro" required>
                                        </div>
                                        <div class="col-lg-2">
                                            <input class="form-control" type="text" id="numero" placeholder="Nº"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-4">
                                            <input class="form-control" type="text" id="complemento"
                                                placeholder="Complemento">
                                        </div>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" id="bairro" placeholder="Bairro"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-9">
                                            <input class="form-control" type="text" id="localidade"
                                                placeholder="Cidade" required>
                                        </div>
                                        <div class="col-lg-3">
                                            <input class="form-control" type="text" id="uf" placeholder="UF"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-6">
                                            <button class="btn btn-soft-orange w-100 mb-2" type="button"
                                                onclick="prevStep(3)">Voltar</button>
                                        </div>
                                        <div class="col-lg-6">
                                            <button class="btn btn-orange w-100 mb-2" type="submit">Finalizar
                                                Cadastro</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <p class="mb-1 mt-5 text-center">
                                <a href="/" class="hover text-orange">
                                    <i class="uil uil-corner-up-left-alt"></i> Voltar para Página Principal
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        let currentStep = 1;

        // function nextStep(step) {
        //     document.getElementById("step" + step).classList.remove("active");
        //     document.getElementById("step" + (step + 1)).classList.add("active");
        //     currentStep++;
        // }

        function nextStep(step) {
            const currentStepDiv = document.getElementById("step" + step);
            const requiredFields = currentStepDiv.querySelectorAll("[required]");
            let valid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add("is-invalid");
                    valid = false;
                } else {
                    field.classList.remove("is-invalid");
                }
            });

            if (!valid) {
                showErrors({
                    geral: ["Preencha todos os campos obrigatórios antes de continuar."]
                });
                return;
            }

            document.getElementById("formErrors").classList.add("d-none");
            currentStepDiv.classList.remove("active");
            document.getElementById("step" + (step + 1)).classList.add("active");
            currentStep++;
        }


        function prevStep(step) {
            document.getElementById("step" + step).classList.remove("active");
            document.getElementById("step" + (step - 1)).classList.add("active");
            currentStep--;
        }

        document.getElementById("multiStepForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const jsonData = {
                name: document.getElementById("name").value,
                cpf: document.getElementById("cpf").value,
                id_gender: document.getElementById("id_gender").value,
                birthdate: document.getElementById("dt_nascimento").value,
                whatsapp: document.getElementById("whatsapp").value,
                eklesia: document.getElementById("eklesia").value,
                id_church: 1,
                email: document.getElementById("email_user").value,
                password: document.getElementById("password").value,
                address: {
                    cep: document.getElementById("cep").value,
                    logradouro: document.getElementById("logradouro").value,
                    numero: document.getElementById("numero").value,
                    complemento: document.getElementById("complemento").value,
                    bairro: document.getElementById("bairro").value,
                    localidade: document.getElementById("localidade").value,
                    uf: document.getElementById("uf").value
                }
            };

            fetch("{{ $url }}/participant", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "username": "{{ $username }}",
                        "token": "{{ $token }}",
                        "id-person": sessionStorage.getItem("auth_id_person")
                    },
                    body: JSON.stringify(jsonData)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        // alert("Erro: " + JSON.stringify(data.messages || data.message));

                        if (data.error && data.messages) {
                            showErrors(data.messages);
                        } else if (data.error && data.message) {
                            showErrors({
                                geral: [data.message]
                            });
                        }
                    } else if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        alert("Cadastro realizado com sucesso!");
                        document.getElementById("multiStepForm").reset();
                    }

                })
                .catch(err => {
                    console.error("Erro:", err);
                    alert("Erro ao enviar os dados.");
                });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const cepInput = document.getElementById("cep");

            cepInput.addEventListener("blur", function() {
                const cep = cepInput.value.replace(/\D/g, '');

                if (cep.length !== 8) {
                    alert("CEP inválido!");
                    return;
                }

                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.erro) {
                            alert("CEP não encontrado!");
                            return;
                        }

                        document.getElementById("logradouro").value = data.logradouro || '';
                        document.getElementById("bairro").value = data.bairro || '';
                        document.getElementById("localidade").value = data.localidade || '';
                        document.getElementById("uf").value = data.uf || '';
                    })
                    .catch(() => {
                        alert("Erro ao buscar o CEP.");
                    });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selectGender = document.getElementById("id_gender");

            fetch("{{ $url }}/gender?sort_by=id&sort_order=asc", {
                    headers: {
                        "Accept": "application/json",
                        "username": "{{ $username }}",
                        "token": "{{ $token }}",
                        "id-person": 1
                    }
                })
                .then(res => res.json())
                .then(data => {

                    console.log("Gêneros recebidos:", data);

                    selectGender.innerHTML = '<option value="">Selecione o Gênero</option>';

                    if (data && data.genders && data.genders.data) {
                        data.genders.data.forEach(g => {
                            selectGender.innerHTML += `<option value="${g.id}">${g.name}</option>`;
                        });
                    } else {
                        selectGender.innerHTML = '<option value="">Nenhum gênero encontrado</option>';
                    }
                })
                .catch(() => {
                    selectGender.innerHTML = '<option value="">Erro ao carregar gêneros</option>';
                });
        });
    </script>

    <script>
        function showErrors(errors) {
            const errorBox = document.getElementById("formErrors");
            errorBox.classList.remove("d-none");

            const messages = {
                "The cpf has already been taken.": "CPF já está cadastrado.",
                "The email has already been taken.": "E-mail já está cadastrado."
            };

            errorBox.innerHTML = Object.entries(errors)
                .map(([field, msgs]) => {
                    const translated = msgs.map(msg => messages[msg] || msg);
                    return `<div><strong>${field}:</strong> ${translated.join(', ')}</div>`;
                })
                .join('');
        }
    </script>

    <script>
        document.getElementById("cpf").addEventListener("blur", function() {
            const cpf = this.value.replace(/\D/g, '');

            if (cpf.length < 11) return;

            fetch("/check-cpf", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').getAttribute(
                            "content")
                    },
                    body: JSON.stringify({
                        cpf
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status && data.result?.data?.length > 0) {
                        showErrors({
                            cpf: ["CPF já está cadastrado."]
                        });
                        document.getElementById("cpf").classList.add("is-invalid");
                    } else {
                        document.getElementById("cpf").classList.remove("is-invalid");
                        document.getElementById("formErrors").classList.add("d-none");
                    }
                })
                .catch(() => {
                    console.error("Erro ao verificar CPF");
                });
        });
    </script>

    <script>
        document.getElementById("email_user").addEventListener("blur", function() {
            const email = this.value.trim();

            if (email.length < 5) return;

            fetch("/check-email", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                            "content")
                    },
                    body: JSON.stringify({
                        email
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status && data.result?.data?.length > 0) {
                        showErrors({
                            email: ["E-mail já está cadastrado."]
                        });
                        document.getElementById("email_user").classList.add("is-invalid");
                    } else {
                        document.getElementById("email_user").classList.remove("is-invalid");
                        document.getElementById("formErrors").classList.add("d-none");
                    }
                })
                .catch(() => {
                    console.error("Erro ao verificar E-mail");
                });
        });
    </script>

    <script>
        document.getElementById("password").addEventListener("blur", function() {
            const password = this.value.trim();
            const passwordField = document.getElementById("password");

            if (password.length < 6) {
                showErrors({
                    password: ["A senha deve ter no mínimo 6 caracteres."]
                });
                passwordField.classList.add("is-invalid");
            } else {
                passwordField.classList.remove("is-invalid");
                document.getElementById("formErrors").classList.add("d-none");
            }
        });
    </script>

    <script>
        document.getElementById("eklesia").addEventListener("blur", function() {
            const eklesia = this.value.trim();
            const eklesiaField = document.getElementById("eklesia");

            if (!eklesia) return;

            fetch("/check-eklesia", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                            "content")
                    },
                    body: JSON.stringify({
                        eklesia
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.result && data.result.data && data.result.data.length > 0) {
                        showErrors({
                            eklesia: ["Este código Eklesia já está cadastrado."]
                        });
                        eklesiaField.classList.add("is-invalid");
                    } else {
                        eklesiaField.classList.remove("is-invalid");
                        document.getElementById("formErrors").classList.add("d-none");
                    }
                })
                .catch(() => {
                    console.error("Erro ao verificar Eklesia");
                });
        });
    </script>


    <style>
        .step {
            display: none;
        }

        .step.active {
            display: block;
        }
    </style>

@endsection
