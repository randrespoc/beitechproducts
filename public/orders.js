$( function() {

    //Obtiene el listado de clientes
    $.get( "/api/customer",{}, function( data ) {
        //Inicializa el listado de clientes
        $("#customers").html("");
        $("#customers").append("<option value=''>Select...</option>");

        //Construye el listado de clientes
        for(let i =0 ; i<data.length; i++){
            let row = data[i];

            $("#customers").append("<option value='" + row.customer_id+ "'>" + row.name + "</option>");
        }
    });

    //Acción para buscar ordenes
    $("#btnLoad").click(function(){
        //Inicializa mensajes de contenido
        $(".data").hide();
        $(".no-data").hide();
        $(".loading").show();

        //Obtiene variables del formulario
        let customerId = $("#customers").val();
        let dateFrom = $("#creation_date_from").val();
        let dateTo = $("#creation_date_to").val();

        //Valida id del cliente
        if(customerId != null && customerId != ""){
            //Valida las fechas ingresadas
            if(validateDate(dateFrom) && validateDate(dateTo)){

                //Obtiene el listado de ordenes
                $.get( "/api/order",{
                    customer_id: customerId,
                    date_ini: dateFrom,
                    date_end: dateTo
                }, function( data ) {
                    buildData(data);
                });

            }else{
                alert("Date inputs are wrong.");
            }
        }else{
            alert("Select a customer.")
        }
        
    });
} );

//Valida el formato de la fecha dada
function validateDate(date){
    return /\d{4}-\d{2}-\d{2}/.test(date);
}

//Construye el cuerpo de la tabla a partir de la estructura de datos proporcionados
function buildData(data){
    $('.data table tbody').html('');
    if(data.length == 0){
        //Si no se obtuvieron datos, se muestra mensaje
        $(".data").hide();
        $(".no-data").show();
        $(".loading").hide();
    }else{
        for(let i=0; i<data.length; i++){
            let row = data[i];

            $('.data table tbody').append("<tr><th scope='row'>" + row.order_id + "</th><td>" + row.creation_date + "</td><td>$" + Intl.NumberFormat().format(row.total) + "</td><td>" + row.delivery_address + "</td><td>" + formatProducts(row.products) + "</td></tr>");
        }
        //Se muestran los datos
        $(".data").show();
        $(".no-data").hide();
        $(".loading").hide();
    }
}

//Se crea el DOM que contiene los productos de cada orden
function formatProducts(products){
    let html = "";
    for(let i=0; i< products.length; i++){
        let prod = products[i];

        html += "<div class='prod'><small>" + prod.quantity + " x </small>" + prod.product_name + " <small>($" + Intl.NumberFormat().format(prod.price_unit) + " unit)</small></div>" ;
    }

    return html;
}