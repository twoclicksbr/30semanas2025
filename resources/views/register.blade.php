@if (session()->has('auth_id_person'))
    @php
        header('Location: ' . route('home'));
        exit();
    @endphp
@endif

@extends('layouts.app')

@section('title', 'Página Inicial')

<style>
    .step {
        display: none;
    }

    .active {
        display: block;
    }
</style>

@section('content')

    <section class="wrapper image-wrapper bg-image bg-overlay bg-overlay-light-100 text-white"
        data-image-src="https://30semanas.com.br//assets/img/photos/bg26.jpg"
        style="background-image: url('https://30semanas.com.br/assets/img/photos/bg26.jpg');">
        <div class="container pt-17 pb-20 pt-md-19 pb-md-21 text-center">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    {{-- <h1 class="display-1 mb-3 text-white"> Alex </h1> --}}

                </div>
            </div>
        </div>
    </section>

    <section class="wrapper mb-10">
        <div class="container pb-14 pb-md-16">
            <div class="row">
                <div class="col-lg-7 col-xl-6 col-xxl-7 mx-auto mt-n20">
                    <div class="card">
                        <div class="card-body p-11 ">


                            <h2 class="mb-3 text-start">Inscreva-se</h2>
                            {{-- <p class="lead mb-6 text-start">Preencha seus Dados.</p> --}}




                            {{-- <form class="text-start mb-3" method="POST" >
                                <div class="form-floating mb-4">
                                    <input type="email" class="form-control" placeholder="Email" id="email"
                                        name="email">
                                    <label for="email">Email: <span class="text-orange"><i
                                                class="uil uil-asterisk"></i></span></label>
                                </div>
                                <div class="form-floating password-field mb-4">
                                    <input type="password" class="form-control" placeholder="Password" id="senha"
                                        name="senha">
                                    <span class="password-toggle"><i class="uil uil-eye"></i></span>
                                    <label for="senha">Senha: <span class="text-orange"><i
                                                class="uil uil-asterisk"></i></span></label>
                                </div>

                                <input type="hidden" name="crud" value="login">
                                <button type="submit"
                                    class="btn btn-orange btn-icon btn-icon-start rounded btn-login w-100 mb-2"><i
                                        class="uil uil-signin"></i> Entrar e Partilhar</button>
                                <!-- <a class="btn btn-orange rounded-pill btn-login w-100 mb-2">Entrar</a> -->
                            </form> --}}

                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif


                            <form id="multiStepForm" method="POST" action="{{ route('register.step1') }}">
                                @csrf

                                <!-- Etapa 1 - Dados pessoais -->
                                <div class="step active" id="step1">

                                    <p class="lead mb-6 text-start">1. Dados Pessoais</p>

                                    <div class="row mb-2">
                                        <div class="col-md-12 col-lg-8">
                                            <input class="form-control" id="name" name="name" placeholder="Nome"
                                                required>
                                        </div>
                                        <div class="col-md-12 col-lg-4">
                                            <select class="form-select" id="id_gender" name="id_gender" required>
                                                <option value="">Gênero</option>
                                                <option value="1">Masculino</option>
                                                <option value="2">Feminino</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-2">

                                        <div class="col-md-12 col-lg-6 position-relative">
                                            <input class="form-control" type="text" id="cpf" name="cpf"
                                                placeholder="CPF" required>
                                            <span id="cpf_spinner"
                                                class="spinner-border spinner-border-sm text-primary position-absolute"
                                                style="right: 10px; top: 50%; transform: translateY(-50%); display: none;"></span>
                                            <span id="cpf_error" style="color: red; display: none;">Este CPF já está
                                                cadastrado.</span>
                                        </div>


                                        <div class="col-md-12 col-lg-6">
                                            <input class="form-control" type="date" id="dt_nascimento"
                                                name="dt_nascimento" required>
                                        </div>
                                    </div>
                                    <div class="row mb-2">

                                        <div class="col-md-12 col-lg-6">
                                            <input class="form-control" type="text" id="whatsapp" name="whatsapp"
                                                placeholder="WhatsApp" required>
                                        </div>

                                        <div class="col-md-12 col-lg-6 position-relative">
                                            <input class="form-control" type="text" id="eklesia" name="eklesia"
                                                placeholder="Eklesia">
                                            <span id="eklesia_spinner"
                                                class="spinner-border spinner-border-sm text-primary position-absolute"
                                                style="right: 10px; top: 50%; transform: translateY(-50%); display: none;"></span>
                                            <span id="eklesia_error" style="color: red; display: none;">Este Eklesia já está
                                                cadastrado.</span>
                                        </div>


                                    </div>

                                    <button type="button" class="btn btn-orange btn-login w-100 mb-2"
                                        onclick="nextStep(1)">Próximo</button>
                                </div>

                                <!-- Etapa 2 - Usuário -->
                                <div class="step" id="step2">


                                    <p class="lead mb-6 text-start">2. Criar Usuário</p>

                                    <div class="row mb-2">

                                        <div class="col-md-12 col-lg-8 position-relative">
                                            <input class="form-control" type="email" id="email_user" name="email"
                                                placeholder="E-mail" required>
                                            <span id="email_spinner"
                                                class="spinner-border spinner-border-sm text-primary position-absolute"
                                                style="right: 10px; top: 50%; transform: translateY(-50%); display: none;"></span>
                                            <span id="email_error" style="color: red; display: none;">Este e-mail já está
                                                cadastrado.</span>
                                        </div>



                                        <div class="col-md-12 col-lg-4">
                                            <input class="form-control" type="password" id="password" name="password"
                                                placeholder="Senha" required>

                                            <script>
                                                document.addEventListener("DOMContentLoaded", function() {
                                                    let passwordInput = document.getElementById("password");
                                                    let passwordError = document.createElement("span");
                                                    passwordError.style.color = "red";
                                                    passwordError.style.display = "none";
                                                    passwordError.textContent = "A senha deve ter pelo menos 8 caracteres.";
                                                    passwordInput.parentNode.appendChild(passwordError);

                                                    passwordInput.addEventListener("blur", function() {
                                                        if (passwordInput.value.length < 8) {
                                                            passwordError.style.display = "block";
                                                            passwordInput.classList.add("is-invalid");
                                                        } else {
                                                            passwordError.style.display = "none";
                                                            passwordInput.classList.remove("is-invalid");
                                                        }
                                                    });
                                                });
                                            </script>

                                        </div>
                                    </div>



                                    <div class="row mb-2">
                                        <div class="col-md-12 col-lg-6">
                                            <button class="btn btn-soft-orange btn-login w-100 mb-2" type="button"
                                                onclick="prevStep(2)">Voltar</button>
                                        </div>
                                        <div class="col-md-12 col-lg-6">
                                            <button class="btn btn-orange btn-login w-100 mb-2" type="button"
                                                onclick="nextStep(2)">Próximo</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Etapa 3 - Endereço -->
                                <div class="step" id="step3">
                                    <p class="lead mb-6 text-start">3. Endereço</p>

                                    <div class="row mb-2">
                                        <div class="col-md-12 col-lg-3">
                                            <input class="form-control" type="text" id="cep" name="cep"
                                                placeholder="CEP" required>
                                        </div>
                                        <div class="col-md-12 col-lg-7">
                                            <input class="form-control" type="text" id="logradouro" name="logradouro"
                                                placeholder="Logradouro" required>
                                        </div>
                                        <div class="col-md-12 col-lg-2">
                                            <input class="form-control" type="text" id="numero" name="numero"
                                                placeholder="N°" required>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-12 col-lg-4">
                                            <input class="form-control" type="text" id="complemento"
                                                name="complemento" placeholder="Complemento">
                                        </div>
                                        <div class="col-md-12 col-lg-8">
                                            <input class="form-control" type="text" id="bairro" name="bairro"
                                                placeholder="Bairro" required>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-12 col-lg-9">
                                            <input class="form-control" type="text" id="localidade" name="localidade"
                                                placeholder="Cidade" required>
                                        </div>
                                        <div class="col-md-12 col-lg-3">
                                            <input class="form-control" type="text" id="uf" name="uf"
                                                placeholder="UF" required>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-12 col-lg-6">
                                            <button class="btn btn-soft-orange btn-login w-100 mb-2" type="button"
                                                onclick="prevStep(3)">Voltar</button>
                                        </div>
                                        <div class="col-md-12 col-lg-6">
                                            <button class="btn btn-orange btn-login w-100 mb-2" type="submit">Finalizar
                                                Cadastro</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <script>
                                let currentStep = 1;

                                function nextStep(step) {
                                    if (validateStep(step)) {
                                        $("#step" + step).removeClass("active");
                                        $("#step" + (step + 1)).addClass("active");
                                        currentStep++;
                                    }
                                }

                                function prevStep(step) {
                                    $("#step" + step).removeClass("active");
                                    $("#step" + (step - 1)).addClass("active");
                                    currentStep--;
                                }

                                function validateStep(step) {
                                    let valid = true;
                                    $("#step" + step + " input, #step" + step + " select").each(function() {
                                        if ($(this).prop("required") && !$(this).val()) {
                                            alert("Preencha todos os campos obrigatórios!");
                                            valid = false;
                                            return false;
                                        }
                                    });
                                    return valid;
                                }
                            </script>

                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    document.getElementById("cep").addEventListener("blur", function() {
                                        let cep = this.value.replace(/\D/g, ''); // Remove caracteres não numéricos

                                        if (cep.length === 8) { // Verifica se tem 8 dígitos
                                            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (!data.erro) {
                                                        document.getElementById("logradouro").value = data.logradouro || "";
                                                        document.getElementById("bairro").value = data.bairro || "";
                                                        document.getElementById("localidade").value = data.localidade || "";
                                                        document.getElementById("uf").value = data.uf || "";
                                                    } else {
                                                        alert("CEP não encontrado!");
                                                    }
                                                })
                                                .catch(error => console.error("Erro ao buscar CEP:", error));
                                        } else {
                                            alert("CEP inválido! Digite um CEP com 8 dígitos.");
                                        }
                                    });
                                });
                            </script>

                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    document.getElementById("email_user").addEventListener("blur", function() {
                                        let email = this.value.trim();
                                        let emailError = document.getElementById("email_error");
                                        let emailSpinner = document.getElementById("email_spinner");

                                        if (email !== "") {
                                            emailSpinner.style.display = "inline-block"; // Mostra o spinner

                                            fetch("/check-email", {
                                                    method: "POST",
                                                    headers: {
                                                        "Content-Type": "application/json",
                                                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                                            .getAttribute("content")
                                                    },
                                                    body: JSON.stringify({
                                                        email: email
                                                    })
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    emailSpinner.style.display = "none"; // Esconde o spinner
                                                    if (data.status === true && data.result && data.result.data.length > 0) {
                                                        emailError.style.display = "block";
                                                        emailError.textContent = "Este e-mail já está cadastrado.";
                                                        document.getElementById("email_user").classList.add("is-invalid");
                                                    } else {
                                                        emailError.style.display = "none";
                                                        document.getElementById("email_user").classList.remove("is-invalid");
                                                    }
                                                })
                                                .catch(error => {
                                                    emailSpinner.style.display = "none"; // Esconde o spinner
                                                    console.error("Erro ao verificar e-mail:", error);
                                                });
                                        }
                                    });
                                });
                            </script>


                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    document.getElementById("cpf").addEventListener("blur", function() {
                                        let cpf = this.value.trim().replace(/\D/g, ""); // Remove pontos e traço
                                        let cpfError = document.getElementById("cpf_error");
                                        let cpfSpinner = document.getElementById("cpf_spinner");

                                        if (cpf !== "") {
                                            cpfSpinner.style.display = "inline-block"; // Mostra o spinner

                                            fetch("/check-cpf", {
                                                    method: "POST",
                                                    headers: {
                                                        "Content-Type": "application/json",
                                                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                                            .getAttribute("content")
                                                    },
                                                    body: JSON.stringify({
                                                        cpf: cpf
                                                    })
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    cpfSpinner.style.display = "none"; // Esconde o spinner
                                                    if (data.status === true && data.result && data.result.data.length > 0) {
                                                        cpfError.style.display = "block";
                                                        cpfError.textContent = "Este CPF já está cadastrado.";
                                                        document.getElementById("cpf").classList.add("is-invalid");
                                                    } else {
                                                        cpfError.style.display = "none";
                                                        document.getElementById("cpf").classList.remove("is-invalid");
                                                    }
                                                })
                                                .catch(error => {
                                                    cpfSpinner.style.display = "none"; // Esconde o spinner
                                                    console.error("Erro ao verificar CPF:", error);
                                                });
                                        }
                                    });
                                });
                            </script>

                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    document.getElementById("eklesia").addEventListener("blur", function() {
                                        let eklesia = this.value.trim(); // Remove espaços extras
                                        let eklesiaError = document.getElementById("eklesia_error");
                                        let eklesiaSpinner = document.getElementById("eklesia_spinner");

                                        if (eklesia !== "") {
                                            eklesiaSpinner.style.display = "inline-block"; // Mostra o spinner

                                            fetch("/check-eklesia", {
                                                    method: "POST",
                                                    headers: {
                                                        "Content-Type": "application/json",
                                                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                                            .getAttribute("content")
                                                    },
                                                    body: JSON.stringify({
                                                        eklesia: eklesia
                                                    })
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    eklesiaSpinner.style.display = "none"; // Esconde o spinner
                                                    if (data.status === true && data.result && data.result.data.length > 0) {
                                                        eklesiaError.style.display = "block";
                                                        eklesiaError.textContent = "Este Eklesia já está cadastrado.";
                                                        document.getElementById("eklesia").classList.add("is-invalid");
                                                    } else {
                                                        eklesiaError.style.display = "none";
                                                        document.getElementById("eklesia").classList.remove("is-invalid");
                                                    }
                                                })
                                                .catch(error => {
                                                    eklesiaSpinner.style.display = "none"; // Esconde o spinner
                                                    console.error("Erro ao verificar Eklesia:", error);
                                                });
                                        }
                                    });
                                });
                            </script>













                            <p class="mb-1 mt-5 text-center">
                                <a href="/" class="hover text-orange"><i class="uil uil-corner-up-left-alt"></i>
                                    Voltar para Página Principal</a>
                            </p>

                            {{-- <hr class="mb-5 mt-5"> --}}

                            <!-- <p class="mb-0 text-center">Não tem uma conta? <a href="https://30semanas.com.br/entrar/novo" class="hover text-orange">Inscrever-se</a></p> -->

                            <!-- <p class="mb-0 text-center">30 semanas na sua Igreja? <a href="https://30semanas.com.br/entrar/novo_igreja" class="hover text-orange">Cadastre sua Igreja</a></p> -->



                            {{-- <p class="mb-2 text-center">
                                Pastor / Líder?
                                <a href="https://30semanas.com.br/entrar/cad_igreja" class="hover text-orange">
                                    Cadastre sua Igreja
                                </a>
                            </p> --}}



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
