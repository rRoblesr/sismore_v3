<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>SISMORE | INICIAR SESION</title>
    <link rel="shortcut icon" href="{{ asset('/') }}public/assets/images/favicon.ico">
    <link href="{{ asset('/') }}public/css/app-terceros-01.min.css" rel="stylesheet" type="text/css" />
    <script src="https://code.jquery.com/jquery-1.11.3.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <style>
        .bg-green-0 {
            background-color: #43beac;
        }

        .bg-orange-0 {
            background: #f04c27;
        }

        .text-white-0 {
            color: #fff;
        }

        .border-success-0 {
            border-color: #43beac !important;
        }

        .bg-success-0 {
            background-color: #43beac !important;
        }

        .btn-success-0 {
            background-color: #43beac !important;
        }

        .table-success-0 {
            background-color: #43beac !important;
        }
    </style>
</head>

<body class="theme-default login">

    <form method="post" action="{{ route('login') }}" id="form1">
        @csrf
        

        <section class="wrapper">
            <aside
                style="background-image: url('{{ asset('/') }}/public/img/fondo1.jpg') !important;background-position: initial;
            background-size: 100%;background-repeat: round;">
                <header>
                    <a class="logo-container">
                        <figure>
                            {{-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                viewbox="0 0 97.15 99.73" height="80">
                                <defs>
                                    <style>
                                        .cls-2,
                                        .cls-3 {
                                            fill-rule: evenodd;
                                        }

                                        .cls-4 {
                                            mask: url(#mask);
                                        }
                                    </style>
                                    <mask id="mask" x="-16.47" y="-19.27" width="119.87" height="113.53"
                                        maskunits="userSpaceOnUse">
                                        <g id="path-1-outside-1">
                                            <rect class="fill-white" x="-16.47" y="-19.27" width="119.87"
                                                height="113.53"></rect>
                                            <path class="cls-2"
                                                d="M93.43,28.16l-.79-.8c-5.09-5-34-31.91-63.22-23.95C9.59,8.81-1.74,29.36,3.2,50.07c.21-.57.43-1.15.67-1.71C-.09,28.6,11,9.45,29.7,4.35,45.39.08,61.07,6.08,73,13.32A108.52,108.52,0,0,1,87.27,23.78c1.72,1.49,3.08,2.75,4,3.64l.25.25-13.3,3.62-1,.27.28.94a40.45,40.45,0,0,1,1.33,18q.41.73.78,1.5A41.53,41.53,0,0,0,78.5,32.23l1-.27,12.83-3.5Z">
                                            </path>
                                        </g>
                                    </mask>
                                </defs>
                                <title>Logo</title>
                                <g id="Capa_2" data-name="Capa 2">
                                    <g id="iso"></g>
                                    <g id="talk2">
                                        <path class="fill-secondary" id="fill"
                                            d="M93.43,28.16l-.79-.8c-5.09-5-34-31.91-63.22-23.95C9.59,8.81-1.74,29.36,3.2,50.07c.21-.57.43-1.15.67-1.71C-.09,28.6,11,9.45,29.7,4.35,45.39.08,61.07,6.08,73,13.32A108.52,108.52,0,0,1,87.27,23.78c1.72,1.49,3.08,2.75,4,3.64l.25.25-13.3,3.62-1,.27.28.94a40.45,40.45,0,0,1,1.33,18q.41.73.78,1.5A41.53,41.53,0,0,0,78.5,32.23l1-.27,12.83-3.5Z">
                                        </path>
                                        <g class="cls-4"></g>
                                        <path class="fill-secondary" id="border"
                                            d="M92.64,27.36l-1.48,1.35h0Zm.79.8L94,30l3.16-.86L95,26.86Zm-64-24.75-.57-1.87ZM3.2,50.07l-2,.43,1.6,6.71L5.14,50.7Zm.67-1.71,1.91.72L6,48.55,5.89,48Zm25.83-44,.56,1.87h0Zm43.26,9,1.11-1.64h0ZM87.27,23.78l1.38-1.44h0Zm4,3.64,1.46-1.36h0Zm.25.25.57,1.87,3.29-.9L93,26.31Zm-13.3,3.62.56,1.87h0Zm-1,.27-.56-1.87-2,.53.57,1.88Zm.28.94,2-.54h0Zm1.33,18-2-.29-.09.62.3.56Zm.78,1.5-1.86.82,2.73,5.56,1.15-6ZM78.5,32.23l-.57-1.88-2,.54.57,1.87Zm1-.27-.57-1.87h0Zm12.83-3.5.57,1.88h0Zm-1.16.25.56.57.14.14s0,0,0,0h0l1.53-1.31L95,26.86h0l0,0-.16-.17L94.12,26ZM30,5.29C43.83,1.52,57.75,6,69.06,12.2a106.17,106.17,0,0,1,22.1,16.51l3-2.69a110,110,0,0,0-23-17.19C59.41,2.36,44.27-2.66,28.85,1.54ZM5.2,49.64C.46,29.77,11.37,10.35,30,5.29L28.85,1.54C7.82,7.27-3.94,29,1.2,50.5ZM5.14,50.7c.2-.54.41-1.08.64-1.62L2,47.63c-.25.6-.49,1.2-.71,1.81Zm24-48.22C9.21,7.9-2.27,28.13,1.86,48.72l4-.73C2.09,29.06,12.7,11,30.26,6.22Zm44.94,9.2C62,4.34,45.68-2,29.13,2.48l1.13,3.74c14.84-4,29.87,1.6,41.59,8.74ZM88.65,22.34A111.32,111.32,0,0,0,74.07,11.68L71.85,15a106,106,0,0,1,14,10.26Zm4.08,3.72c-.94-.91-2.32-2.2-4.08-3.72l-2.76,2.88c1.69,1.47,3,2.7,3.91,3.57Zm.26.25-.26-.25L89.8,28.79l.25.24ZM78.78,33.16l13.31-3.62L91,25.8,77.65,29.42Zm-1,.27,1-.27-1.13-3.74-1,.27ZM79.49,32,79.2,31,75.26,32.1l.28.93Zm1.38,18.79A42.5,42.5,0,0,0,79.49,32L75.54,33a38.86,38.86,0,0,1,1.27,17.15ZM77,51.36c.26.47.5,1,.74,1.43l3.72-1.65c-.26-.52-.53-1-.82-1.57Zm-.49-18.6A39.54,39.54,0,0,1,77.6,51.62l4,.69a43.41,43.41,0,0,0-1.17-20.62Zm2.39-2.67-1,.26,1.14,3.75,1-.27Zm12.84-3.5-12.84,3.5,1.13,3.74,12.84-3.49Zm1.1-.3-1.1.3,1.13,3.75L94,30Z">
                                        </path>
                                    </g>
                                    <g id="talk1">
                                        <path class="fill-secondary"
                                            d="M72.72,87.19C49,109.71,4.1,95,4.1,95l11-10.42C-1.59,68.76-2.83,44.25,12.34,29.86s41-13.23,57.64,2.61S87.89,72.8,72.72,87.19Z">
                                        </path>
                                    </g>
                                    <g id="chart">
                                        <path class="fill-white"
                                            d="M47.9,73.44V40.27H58.09V73.44Zm-23.29,0V59.62H34.8V73.44ZM43.53,52.71v18H39.17v-18Zm2.91-2.77H36.25v23.5H46.44Z">
                                        </path>
                                    </g>
                                </g>
                            </svg> --}}
                            <br><br>
                            <figcaption>
                                
                                <h1 class="brand">SISMORE</h1>
                                <p class="small">Sistema de Monitoreo Regional</p>
                            </figcaption>
                        </figure>
                    </a>
                </header>

                <footer>
                    <!-- <h1> <a href="http://www.minedu.gob.pe"> </a> </h1> -->
                </footer>
            </aside>

            <main>
                <div id="content">
                    <h1 class="text-center mb-5">
                        <a href="#"><img src="{{ asset('public/img/logoblanco.png') }}" width="215"
                                class="img-fluid"></a>
                    </h1>
                    <h4 class="text-center mb-0">Inicia sesión</h4>
                    <p class="text-center">Por favor, ingresa tus credenciales</p>

                    <div class="form-label-group">
                        <input name="usuario" type="text" id="usuario" class="form-control"
                            placeholder="Usuario" />

                        <label id="lblUsuario" for="usuario">Usuario</label>
                    </div>

                    <div class="form-label-group">
                        <input name="password" type="password" id="password" class="form-control"
                            placeholder="Contraseña" />
                        <label id="lblClave" for="password">Contraseña</label>

                    </div>

                    <!-- <div id="Panel1" class="form-group" onkeypress="javascript:return WebForm_FireDefaultButton(event, &#39;btnAceptar&#39;)"> -->
                    <div id="Panel1" class="form-group">

                        <!-- <input type="submit" name="btnAceptar" value="Ingresar" id="btnAceptar" class="btn btn-lg btn-secondary btn-block" /> -->
                        <input type="submit" name="btnAceptar" value="Ingresar" id="btnAceptar"
                            class="btn btn-lg btn-success-0 btn-block" />

                    </div>
                    <div class="checkbox mb-3 mt-3">
                        <div class="float-left">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" id="rememberme" type="checkbox" name="rememberme"
                                    checked>
                                <label class="custom-control-label" for="rememberme">Recordar</label>
                            </div>
                        </div>
                        <label for=""></label>
                        <div class="float-right"><a id="btnmodal" href="#" data-toggle="modal"
                                data-target="#divCambioContraseña" style="color:#007bff">Olvidé mi clave</a></div>
                    </div>

                    <div class="form-label-group"></div>
                </div>

            </main>
        </section>

        {{-- <div class="modal inmodal" id="divCambioContraseña" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content animated bounceInRight">
                    <div class="modal-header">
                        <h4 class="modal-title">Actualizacion de credenciales</h4>
                        <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <i class="fa fa-laptop modal-icon"></i>
                    </div>
                    <div class="modal-body">
                        <div class="form-label-group">
                            <input name="txtUsuario1" type="text" id="txtUsuario1" class="form-control"
                                placeholder="Usuario" />

                            <label id="txtUsuario2" for="txtUsuario1">Usuario</label>
                        </div>

                        <div class="form-label-group">
                            <input name="txtClave1" type="password" id="txtClave1" class="form-control"
                                placeholder="Contraseña" />
                            <label id="txtClave3" for="txtClave1">Contraseña</label>

                        </div>

                        <div class="form-label-group">
                            <input name="txtClave2" type="password" id="txtClave2" class="form-control"
                                placeholder="Repita Contraseña" />
                            <label id="txtClave4" for="txtClave2">Contraseña</label>

                        </div>
                        <div class="form-label-group" id="divCodigoVerifica">
                            <input name="txtCodigoVerificacion" type="text" id="txtCodigoVerificacion"
                                disabled="disabled" class="aspNetDisabled" class="form-control"
                                placeholder="Código Verificación" />
                            <label id="Label1" for="txtCodigoVerificación">Codigo Verificación</label>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="btnCancelar" value="Cancelar" id="btnCancelar" type="button"
                            class="btn btn-danger" data-dismiss="modal" />
                        <input type="submit" name="btnActualizar" value="Actualizar" id="btnActualizar"
                            type="button" class="btn btn-primary" />
                        <div class="alert"> </div>

                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <script type="text/javascript">
            //<![CDATA[
            var theForm = document.forms['form1'];
            if (!theForm) {
                theForm = document.form1;
            }

            function __doPostBack(eventTarget, eventArgument) {
                if (!theForm.onsubmit || (theForm.onsubmit() != false)) {
                    theForm.__EVENTTARGET.value = eventTarget;
                    theForm.__EVENTARGUMENT.value = eventArgument;
                    theForm.submit();
                }
            }
            //]]>
        </script> --}}

{{--         <script
            src="/WebResource.axd?d=pynGkmcFUV13He1Qd6_TZBMjd1ZBAaat8alifBLD1Up6L1bKzb6yQiGXAKCplDrDnsdt00LQ52pb5Mj0n6usMg2&amp;t=637823185705833095"
            type="text/javascript"></script> --}}
    </form>
    {{-- <script src="../dist/scripts/app.min.js"></script> --}}
    <script type="text/javascript">
        /* function ingresar(field, event) {
                var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
                if (event.keyCode == 13) {
                    var obj = document.getElementById("btnAceptar.ClientID");
                    obj.click();
                }
            }

            function Enter(field, event) {
                var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
                if (keyCode == 13) {
                    var i;
                    for (i = 0; i < field.form.elements.length; i++)
                        if (field == field.form.elements[i])
                            break;
                    i = (i + 1) % field.form.elements.length;
                    field.form.elements[i].focus();
                    return false;
                } else
                    return true;
            } */
    </script>
</body>
<script></script>

</html>
