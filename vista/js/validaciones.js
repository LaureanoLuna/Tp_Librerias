// --------------------------- INICIO VALIDACIONES ---------------------------
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form){
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// --------------- FORMULARIO DE INICIO DE SESIÓN ---------------
$('#logeo').bootstrapValidator({
    feedbackIcons: {
        valid: 'fas fa-check',
        invalid: 'fas fa-times',
        validating: 'fas fa-refresh'
    },
    fields: {
        username: {
            validators: {
                notEmpty: {
                    message: 'Debe ingresar un nombre de usuario'
                },
            }
        },
        password: {
        validators: {
            notEmpty: {
                message: 'Debe ingresar su contraseña. '
            },
            stringLength: {
                min: 8,
                message: 'La contraseña tener un mínimo de 8 caracteres'
            },
        }
        }
    }
});

// --------------- FORMULARIO DE REGISTRO ---------------
$('#registro').bootstrapValidator({
    feedbackIcons: {
        valid: 'fas fa-check',
        invalid: 'fas fa-times',
        validating: 'fas fa-refresh'
    },
    fields: {
        email:{
            validators: {
                notEmpty: {
                    message: 'Debe ingresar su correo. '
                },
                regexp: {
                    regexp: /^[\w-\.]+@([\w-]+\.)+[\w-]{1,4}$/g,
                } 
            }
        },
        username: {
            validators: {
                notEmpty: {
                    message: 'Debe ingresar un nombre de usuario'
                },
            }
        },
        password: {
        validators: {
            notEmpty: {
                message: 'Debe ingresar su contraseña. '
            },
            stringLength: {
                min: 8,
                message: 'La contraseña tener un mínimo de 8 caracteres'
            },
        }
        }
    }
});

// --------------- FORMULARIO DE CONFIRMAR CORREO ---------------
$('#confirmar').bootstrapValidator({
    feedbackIcons: {
        valid: 'fas fa-check',
        invalid: 'fas fa-times',
        validating: 'fas fa-refresh'
    },
    fields: {
        selector:{
            validators: {
                notEmpty: {
                    message: 'Debe ingresar el selector. '
                },
                stringLength: {
                    min: 16,
                    message: 'El selector tiene un mínimo de 16 caracteres'
                },
            }
        },
        token: {
            validators: {
                notEmpty: {
                    message: 'Debe ingresar el token. '
                },
                stringLength: {
                    min: 16,
                    message: 'El token tiene un mínimo de 16 caracteres'
                },
            }
        }
    }
});

// --------------- FORMULARIO DE GENERAR TOKEN ---------------
$('#generar').bootstrapValidator({
    feedbackIcons: {
        valid: 'fas fa-check',
        invalid: 'fas fa-times',
        validating: 'fas fa-refresh'
    },
    fields: {
        selector:{
            validators: {
                notEmpty: {
                    message: 'Debe ingresar el selector. '
                },
                stringLength: {
                    min: 16,
                    message: 'El selector tiene un mínimo de 16 caracteres'
                },
            }
        },
        token: {
            validators: {
                notEmpty: {
                    message: 'Debe ingresar el token. '
                },
                stringLength: {
                    min: 16,
                    message: 'El token tiene un mínimo de 16 caracteres'
                },
            }
        }
    }
});

// --------------- FORMULARIO DE CONSULTA ---------------
$('#Consulta').bootstrapValidator({
    feedbackIcons: {
        valid: 'fas fa-check',
        invalid: 'fas fa-times',
        validating: 'fas fa-refresh'
    },
    fields:{
        nombre: {
            validators: {
                notEmpty: {
                    message: 'Debe ingresar un nombre. '
                },
                stringLength: {
                    min: 3,
                    message: 'El nombre debe tener al menos 3 caracteres. '
                },
                regexp: {
                    regexp: /^[a-zA-ZñáéíóúüÁÉÍÓÚÜÑ\s]+$/,
                    message: 'El nombre solo debe llevar letras. '
                } 
            }
        },
        apellido: {
            validators: {
                notEmpty: {
                    message: 'Debe ingresar un apellido. '
                },
                stringLength: {
                    min: 3,
                    message: 'El apellido debe tener al menos 3 caracteres. '
                },
                regexp: {
                    regexp: /^[a-zA-ZñáéíóúüÁÉÍÓÚÜÑ\s]+$/,
                    message: 'El apellido solo debe llevar letras. '
                } 
            }
        },
        email:{
            validators: {
                notEmpty: {
                    message: 'Debe ingresar su correo. '
                }
            }
        },
        comentario:{
            validators: {
                notEmpty: {
                    message: 'Debe ingresar una consulta. '
                }
            }
        }
    }
})


//
//  ⢀⡴⠑⡄⠀⠀⠀⠀⠀⠀⠀⣀⣀⣤⣤⣤⣀⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀ 
//  ⠸⡇⠀⠿⡀⠀⠀⠀⣀⡴⢿⣿⣿⣿⣿⣿⣿⣿⣷⣦⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀ 
//  ⠀⠀⠀⠀⠑⢄⣠⠾⠁⣀⣄⡈⠙⣿⣿⣿⣿⣿⣿⣿⣿⣆⠀⠀⠀⠀⠀⠀⠀⠀ 
//  ⠀⠀⠀⠀⢀⡀⠁⠀⠀⠈⠙⠛⠂⠈⣿⣿⣿⣿⣿⠿⡿⢿⣆⠀⠀⠀⠀⠀⠀⠀ 
//  ⠀⠀⠀⢀⡾⣁⣀⠀⠴⠂⠙⣗⡀⠀⢻⣿⣿⠭⢤⣴⣦⣤⣹⠀⠀⠀⢀⢴⣶⣆ 
//  ⠀⠀⢀⣾⣿⣿⣿⣷⣮⣽⣾⣿⣥⣴⣿⣿⡿⢂⠔⢚⡿⢿⣿⣦⣴⣾⠁⠸⣼⡿ 
//  ⠀⢀⡞⠁⠙⠻⠿⠟⠉⠀⠛⢹⣿⣿⣿⣿⣿⣌⢤⣼⣿⣾⣿⡟⠉⠀⠀⠀⠀⠀ 
//  ⠀⣾⣷⣶⠇⠀⠀⣤⣄⣀⡀⠈⠻⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡇⠀⠀⠀⠀⠀⠀ 
//  ⠀⠉⠈⠉⠀⠀⢦⡈⢻⣿⣿⣿⣶⣶⣶⣶⣤⣽⡹⣿⣿⣿⣿⡇⠀⠀⠀⠀⠀⠀ 
//  ⠀⠀⠀⠀⠀⠀⠀⠉⠲⣽⡻⢿⣿⣿⣿⣿⣿⣿⣷⣜⣿⣿⣿⡇⠀⠀⠀⠀⠀⠀ 
//  ⠀⠀⠀⠀⠀⠀⠀⠀⢸⣿⣿⣷⣶⣮⣭⣽⣿⣿⣿⣿⣿⣿⣿⠀⠀⠀⠀⠀⠀⠀ 
//  ⠀⠀⠀⠀⠀⠀⣀⣀⣈⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠇⠀⠀⠀⠀⠀⠀⠀ 
//  ⠀⠀⠀⠀⠀⠀⢿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠃⠀⠀⠀⠀⠀⠀⠀⠀ 
//  ⠀⠀⠀⠀⠀⠀⠀⠹⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡿⠟⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀ 
//  ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠉⠛⠻⠿⠿⠿⠿⠛⠉
//