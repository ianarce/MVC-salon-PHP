let paso =1;
const pasoInicial=1;
const pasoFinal=3;

const cita ={
    id:'',
    nombre : '',
    fecha : '',
    hora: '',
    servicios:[]
}

document.addEventListener('DOMContentLoaded',function(){

    iniciarApp();
   
})

function iniciarApp(){
    tabs();
    mostrarSeccion();//Muestra y oculta las secciones
    botonesPaginador();//Agrega o quita los botones del paginador
    paginaAnterior();
    paginaSiguiente();
   
    consultarAPI(); //consultar la Api

    nombreCliente();//Añade el nombre del cliente al objeto cita
    getId();
    seleccionarFecha()
    seleccionarHora();
    mostrarResumen();
}

function mostrarSeccion(){
    const seccionAnterior = document.querySelector('.mostrar');//selecciona la clase mostrar para remover el display
    if(seccionAnterior)[
        seccionAnterior.classList.remove('mostrar')//remueve el display para que solo uno aparezca y no varios

    ]
    
    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add('mostrar');
    

    const tab = document.querySelector(`[data-paso="${paso}"]`)
    const tabAnterior = document.querySelector(".actual")
    if(tabAnterior){
        tabAnterior.classList.remove('actual');
    }
    tab.classList.add('actual');
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button'); //seleccionamos los botones de la clase .tabs
    
    botones.forEach((boton)=>{
        boton.addEventListener('click',function(e){
            paso = parseInt(e.target.dataset.paso)
            mostrarSeccion();
            botonesPaginador();
        })
    })
}

function botonesPaginador(){
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if(paso===1){
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }else if(paso===3){
        mostrarResumen();
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar')
    }else{
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }
    mostrarSeccion();
}

function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click',function(){
        
        if(paso<=pasoInicial) return
        paso--;
        botonesPaginador();
    })
}

function paginaSiguiente(){
        const paginaSiguiente = document.querySelector('#siguiente')
        paginaSiguiente.addEventListener('click',function(){
            if(paso>=pasoFinal)return;

            paso++
            botonesPaginador();
        })
}

async function consultarAPI(){
    try{
        const url = `${location.origin}/api/servicios`;

        const resultado = await fetch(url);
        const respuesta = await resultado.json();

        mostrarServicios(respuesta);
    }catch (error){
        console.log(error);
    }

}

function mostrarServicios(servicios){
    servicios.forEach(servicio =>{
        const {id, nombre, precio} = servicio

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$ ${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio =id;
        servicioDiv.onclick = function (){
            seleccionarServicio(servicio);
        }  //crear una funcion anonima para poder hacer el callback

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);
        
        document.querySelector('#servicios').appendChild(servicioDiv);
    })
}


function seleccionarServicio(servicio){
    const { id } = servicio
    const {servicios} =cita;
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`)

    if(servicios.some(agregado => agregado.id===servicio.id)){ //array function para encontrar si hay un servicio igual
        //si ya esta agregado hay que eliminarlo con un array method llamado filter
        cita.servicios = servicios.filter(agregado => agregado.id !=servicio.id)
        divServicio.classList.remove('seleccionado')
        console.log(cita)
    }else{
        //si no esta agregado entonces hay que agregarlo y agregar la clase de css
        cita.servicios = [...servicios,servicio];
        divServicio.classList.add('seleccionado');
    }
  
    
    //css 
    
    
    
    
}
function nombreCliente(){
    cita.nombre = document.querySelector('#nombre').value
    
}

function getId(){
    cita.id = document.querySelector('#id').value
}
function mostrarAlerta(mensaje,tipo,elemento,desaparecer=true){
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia){
        alertaPrevia.remove();
    }; //si ya existe una alerta anterior entonces no se ejecutara
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta')
    alerta.classList.add(tipo)

    const seccion = document.querySelector(elemento);
    seccion.appendChild(alerta);

    if(desaparecer){
        setTimeout(()=>{
            alerta.remove()
        },2000)
    }
   
}

function seleccionarFecha(){
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input',function(e){
        const dia = new Date(e.target.value).getUTCDay();
        
        if([0,6].includes(dia)){
            e.target.value = ''
            mostrarAlerta('Fines de semana no permitido','error','.formulario')
        }else{
            cita.fecha=e.target.value;
        }
    })
   
}

function seleccionarHora(){
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input',function(e){
        const horaCita=e.target.value
        const hora=horaCita.split(":")[0];

        if(hora<10 || hora>18){
            mostrarAlerta('Horas no validas','error','.formulario')
            e.target.value=''
        }else{
            cita.hora=e.target.value;
            
        }
    })
}
function mostrarResumen(){
    const citaResumen = document.querySelector('.contenido-resumen')

    while(citaResumen.firstChild){
        citaResumen.removeChild(citaResumen.firstChild)
    }

    if(Object.values(cita).includes('')|| cita.servicios.length===0){
        mostrarAlerta('Faltan datos o servicios por agregar','error','.contenido-resumen',false)
        return;
    }

    //formatear los datos de la  cita 
    const {nombre,fecha,hora,servicios} = cita

   
    const headingServicios = document.createElement('H3');
    headingServicios.innerHTML = 'Tus Servicios'
    citaResumen.appendChild(headingServicios)

    servicios.forEach(servicio=>{
        const {id, nombre, precio} = servicio

        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio')
        
        const textoServicio = document.createElement('P')
        textoServicio.innerHTML = nombre

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio: </span>$${precio}`

        contenedorServicio.appendChild(textoServicio)
        contenedorServicio.appendChild(precioServicio)
        
        citaResumen.appendChild(contenedorServicio)
    })
    const headingCita = document.createElement('H3')
    headingCita.innerHTML = 'Informacíon cita'
    citaResumen.appendChild(headingCita);
    
    const nombreCliente = document.createElement('P')
    nombreCliente.innerHTML = `<span>Nombre:</span>${nombre}`
    citaResumen.appendChild(nombreCliente);
    
    const fechaobj = new Date(fecha);
    const mes = fechaobj.getMonth();
    const dia = fechaobj.getDate() +2;
    const año = fechaobj.getFullYear();

    const fechaUTC = new Date(Date.UTC(año,mes,dia))
    const opciones = {weekday:'long',year:'numeric',month:'long',day:'numeric'}
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX',opciones);
    

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML= `<span>Fecha: </span>${fechaFormateada}`
    citaResumen.appendChild(fechaCita);

    const horaCita= document.createElement('P');
    horaCita.innerHTML = `<span>Hora: </span>${hora}`
    citaResumen.appendChild(horaCita);

    const botonReservar = document.createElement('BUTTON')
    botonReservar.classList.add('boton')
    botonReservar.textContent='Reservar Cita';
    botonReservar.onclick = reservarCita;
    
    citaResumen.appendChild(botonReservar)
}


async function reservarCita(){

    const {nombre,fecha,hora,servicios,id}=cita;
    const idServicio = servicios.map(servicio =>servicio.id)
    const datos = new FormData();
    datos.append('usuarioId',id);

    datos.append('fecha',fecha)
    datos.append('hora',hora)
    datos.append('servicios',idServicio)

    try {
        const url = `${location.origin}/api/citas` //para obtener el link del dominio

        const respuesta = await fetch(url,{
            method:'POST',
            body:datos
        })
        const resultado = await respuesta.json()
        
        if(resultado.resultado){
            Swal.fire({
                title: "Cita creada",
                text: "Tu cita fue creada correctamente",
                icon: "success",
             
              }).then(()=>{
                window.location.reload()
              });
        }
        
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un problema al generar la cita !",
            
          });
         
    }
        
}