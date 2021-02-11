/**
 * @type {Document}
 */
var Doc = document;

/**
 * @type {Window}
 */
var Win = window;

function disableButton(btn){
    document.getElementById(btn.id).disabled = true;
    //alert("Button has been disabled.");
}

var Modal = {
    html: '<div class="modal fade" tabindex="-1" role="dialog">' +
        '  <div class="modal-dialog" role="document">' +
        '    <div class="modal-content">' +
        '      <div class="modal-header">' +
        '        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
        '        <h4 class="modal-title"></h4>' +
        '      </div>' +
        '      <div class="modal-body">' +
        '      </div>' +
        '      <div class="modal-footer">' +
        '        <button type="button" class="btn" id="btn-cancel" data-dismiss="modal"></button>' +
        '        <button type="button" class="btn" id="btn-confirm"></button>' +
        '      </div>' +
        '    </div>' +
        '  </div>' +
        '</div>',
    alert: function(options) {
        var defaultOptions = {
            title: 'Ops!',
            content: 'Não foi possível processar as informações',
            btCancelText: 'Fechar',
            type: 'default'
        };
        var $modal = this.getModalObject();

        var opts = this.loadOptions(defaultOptions, options);

        var $btCancel = $modal.find('#btn-cancel');

        $modal.find('.modal-title').html(opts.title);
        $modal.find('.modal-body').html(opts.content);
        $modal.find('#btn-confirm').addClass('hidden');

        $btCancel.html(opts.btCancelText);
        $btCancel.addClass('btn-' + opts.type);

        $modal.modal('show');
    },
    /**
     * Confirmation
     *
     * @param {Object} options {title, content, btConfirm, btConfirmType, btCancel, btCancelType, callbackConfirm, callbackCancel}
     * @return {VoidFunction}
     */
    confirmation: function(options) {
        var defaultOptions = {
            title: 'Confirmação',
            content: 'Deseja realmente executar esta ação?',
            btConfirm: 'Confirmar',
            btConfirmType: 'primary',
            btCancel: 'Cancelar',
            btCancelType: 'default',
            callbackConfirm: null,
            callbackCancel: null
        };

        var $modal = this.getModalObject();

        var opts = this.loadOptions(defaultOptions, options);

        // Remove close button
        $modal.find('.modal-header button').addClass('hidden');

        // Texts
        $modal.find('.modal-title').html(opts.title);
        $modal.find('.modal-body').html(opts.content);

        // Button Confirm
        var $btConfirm = $modal.find('#btn-confirm');
        $btConfirm.html(opts.btConfirm);
        $btConfirm.addClass('btn-' + opts.btConfirmType);

        // Button Cancel
        var $btCancel = $modal.find('#btn-cancel');
        $btCancel.html(opts.btCancel);
        $btCancel.addClass('btn-' + opts.btCancelType);

        // Event confirm click
        $btConfirm.click(function(e) {
            e.preventDefault();
            if (typeof opts.callbackConfirm === 'function') {
                opts.callbackConfirm($modal);
            } else {
                $modal.modal('hide');
            }
        });

        // Event cancel click
        if (typeof opts.callbackCancel === 'function') {
            $btCancel.removeAttr('data-dismiss');
            $btCancel.click(function(e) {
                e.preventDefault();
                opts.callbackCancel($modal);
            });
        }

        $modal.modal('show');
    },
    /**
     * Progress
     *
     * @param {Object} options {
     * title, content, type, style}
     * @returns {{hide: hide, setProgress: setProgress}}
     */
    progress: function(options) {
        var defaultOptions = {
            title: 'Ops!',
            content: '',
            type: 'stripes-animated',
            style: 'success'
        };

        var opts = this.loadOptions(defaultOptions, options);

        var progressHtml = Doc.createElement('div');
        progressHtml.className = 'progress';
        progressHtml.innerHTML = '<div class="progress-bar progress-bar-{style} progress-bar-{type}" role="progressbar" aria-valuenow="0" ' +
            'aria-valuemin="0" aria-valuemax="100">' +
            '</div>';

        var progressBar = progressHtml.firstChild;

        var $modal = this.getModalObject();

        var $modalBody = $modal.find('.modal-body');

        var body = '<p>' + opts.content + '</p>';

        $modal.find('.modal-header button').addClass('hidden');
        $modal.find('.modal-footer').addClass('hidden');
        $modal.find('.modal-title').html(opts.title);

        if (opts.type === 'stripes-animated') {
            progressBar.className = progressBar.className.replace('{style}', opts.style);
            progressBar.className = progressBar.className.replace('{type}', 'striped active');
            progressBar.setAttribute('aria-valuenow', 100);
            progressBar.style.width = '100%';
        } else if (opts.type === 'stripes') {
            progressBar.className = progressBar.className.replace('{style}', opts.style);
            progressBar.className = progressBar.className.replace('{type}', 'striped');
        }

        $modalBody.append(body);
        $modalBody.append(progressHtml);

        var $progressBar = $modalBody.find('.progress-bar');

        $modal.modal('show');

        var setProgress = function(percentage) {
            $progressBar.attr('aria-valuenow', percentage);
            $progressBar.css('width', percentage + '%');
            $progressBar.html(percentage + '%');
        };

        var hide = function() {
            $modal.modal('hide');
        };

        return {setProgress:setProgress,hide:hide};
    },
    loadOptions: function(defaultOptions, options) {
        for (var key in defaultOptions) {
            if (options.hasOwnProperty(key)) {
                defaultOptions[key] = options[key];
            }
        }
        return defaultOptions;
    },
    /**
     * @returns {c.fn.init|b.fn.init|jQuery|HTMLElement}
     */
    getModalObject: function() {
        return $(this.html);
    }
};

var CommandCopy = function() {
    Doc.execCommand('copy');
};

var CreateTextClipboard = function (selector) {
    var elements = typeof selector === 'string' ? document.querySelectorAll(selector) : selector;

    if (elements === null || typeof elements !== 'object') {
        return ;
    }

    var listElements = [], listTooltip = [];
    if (elements.length === undefined) {
        listElements.push(elements);
    } else {
        listElements = elements;
    }

    var showTextCopied = function(position) {
        listTooltip[position].style.visibility = 'visible';
        setTimeout(function () {
            listTooltip[position].style.visibility = 'hidden';
        }, 1000);
    };

    var getTooltipTextCopied = function(position) {
        var div = document.createElement('div');
        div.setAttribute('style', 'background-color:#DCDCDC;padding:2px 8px;border-radius:5px;position:absolute;top:-15px;right:0px;visibility: hidden;transition: visibility 1s');
        div.innerHTML = 'Copiado';
        div.id = 'copyclipboard-tooltip-' + position;

        listTooltip[position] = div;

        return div;
    };

    var clickClipboardButton = function() {
        var position = this.position;
        var input = listElements[position].firstChild;
        input.select();
        document.execCommand('copy');
        input.blur();
        showTextCopied(position);
    };

    var getClipboardButton = function(position) {
        var span = document.createElement('span');
        span.className = 'glyphicon glyphicon-copy';
        span.setAttribute('data-position', position);
        span.setAttribute('style', 'cursor:pointer;font-size:20px;position:absolute;right:10px;top:5px');
        span.addEventListener('click', clickClipboardButton.bind({position:position}));

        return span;
    };

    var createInputHtmlReadOnly = function(value) {
        var input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control';
        input.readOnly = true;
        input.setAttribute('value', value);

        return input;
    };

    for (var key in listElements) {
        if (listElements.hasOwnProperty(key)) {
            var input = createInputHtmlReadOnly(listElements[key].innerHTML);
            listElements[key].setAttribute('style', 'position: relative');
            listElements[key].innerHTML = '';
            listElements[key].appendChild(input);
            listElements[key].appendChild(getClipboardButton(key));
            listElements[key].appendChild(getTooltipTextCopied(key));
        }
    }
};

var Profile = {
    check: function (id) {
        if ($.trim($("#" + id).value) == '') {
            $("#" + id).focus();
            $("#" + id + "_alert").show();

            return false;
        };

        return true;
    },
    validate: function (form) {
        if (SignUp.check("name") == false) {
            return false;
        }
        if (SignUp.check("email") == false) {
            return false;
        }
        $("#profileForm").submit();
    }
};

var SignUp = {
    check: function (id) {
        if ($.trim($("#" + id).value) == '') {
            $("#" + id).focus();
            $("#" + id + "_alert").show();

            return false;
        };

        return true;
    },
    validate: function (button) {
        var error=false;
        button.disabled = true;
        if ($("#name").val() == '') {
            $("#name_alert").show();
            error=true;
        }
        /*if ($("#email").val() == '') {
            $("#email_alert").show();
            error=true;
        }*/
        if ($("#password").val() == '' || $("#password").val().length<8)  {
            $("#password_alert").show();
            error=true;
        }
        if ($("#password").val() != $("#repeatPassword").val()) {
            $("#repeatPassword_alert").show();
            error=true;
        }
        if(error){
            return false;
        }else{
            button.enabled = true;
            $("#registerForm").submit();
        }
    }
}

var SignIn = {
    // check: function (id) {
    //     if ($.trim($("#" + id).value) == '') {
    //         $("#" + id).focus();
    //         $("#" + id + "_alert").show();

    //         return false;
    //     };

    //     return true;
    // },
    retrieve: function (button) {
        var error=false;
        button.disabled = true;
        if ($("#code").val() == '') {
            $("#code_alert").show();
            error=true;
        }
        /*
        if ($("#email").val() == '') {
            $("#email_alert").show();
            error=true;
        }
        */

        if ($("#password").val() == '' || $("#password").val().length<8)  {
            $("#password_alert").show();
            error=true;
        }
        if ($("#password").val() != $("#repeatPassword").val()) {
            $("#repeatPassword_alert").show();
            error=true;
        }
        if(error){
            return false;
        }else{
            button.enabled = true;
            $("#retrieveForm").submit();
        }
    }
}

var Form = {
    reset : function() {
        $("#mySubmit").attr('disabled',false);
        $("#registerForm .alert").hide();
        $("div.profile .alert").hide();
    }
}

var User = {
    changeUserType: function(){
        var type=$('select#usuario_tipo').val();
        if (type==1 || type==2) {
            $('#vendedor').parent().hide();
        } else {
            $('#vendedor').parent().show();
        }
    },
    changeStatus : function(id,element) {
        var userStatus = '';
        var elementLabel = '';
        if ($(element).val()=="ativar"){userStatus='active',elementLabel = 'inativar'}
        else if ($(element).val()=="inativar"){userStatus='inactive',elementLabel = 'ativar'}
        var uri =  window.location.protocol+'//'+window.location.host+"/usuario/changeStatus";
        $.ajax({
            type: "POST",
            data: jQuery.param({ id: id,state:userStatus}) ,
            customResponse: userStatus,
            label: elementLabel,
            url:uri,
            success: function(result){
                if(result == 'ok'){
                    $('#user_status_'+id).html(
                        '<span class="label label-primary">' + this.customResponse + '</span>'
                    );
                    $(element).val(this.label);
                    Header.update();
                } else {
                    $('.container').prepend('<div class="alert alert-danger in-tpl-message">ocorreu um erro!</div>');
                    
                    //danger will robson
                }
            }
        });
    },
    toggleStatus : function(idUser, toStatus, domBtt){
        var uri =  window.location.protocol+'//'+window.location.host+"/usuario/changeStatus";
        $(domBtt).button('loading');
        $.ajax(
            {
                type: "POST",
                data: jQuery.param(
                    {
                        id:idUser,
                        state:toStatus
                    }
                ) ,
                url:uri,
                success: function(result){
                    if(result == 'ok'){
                        
                        $('#user-status-alert').hide();
                        $('#row-user-'+idUser).attr('data-row-status',toStatus);
                        $('#user_status_'+idUser).html(
                            '<span class="label label-primary">' + toStatus + '</span>'
                        );
                        $(domBtt).button('reset');
                        Header.update();
                    } else {
                        $('#user-status-alert').show();
                        
                        $('#user-status-alert').show();
                        $(domBtt).button('reset');
                        //danger will robson
                    }
                }
            }
        );
    },
    changeCompany : {
        toggle : function(element){
            if ($(element).html()=='alterar') {
                $(element).html('cancelar');
                $("#step-wrapper").show();
            } else {
                $(element).html('alterar');
                $("#step-wrapper").hide();
            }
        }
    }
};

var Client = {
    edit :  {
        selectClientType : function(id){
            $('select#cliente_tipo').val(id);
        },
        initialize : function(){
            var type = $('#cliente_tipo').val();
            $('#id').val($('#id').val()); // Workaround for phalcon. There is a bug with relations between models. This certifies that we are updating the correct client
            $('#cliente_tipo').on('change',function(){
                $('#tipo').val(this.value);
            });
        }
    },
    update : function(){
        $('#usuario_atualizou').val(1);
    }
}

var Cart = {
    Product : {
        add    : function(idProduct,quantity){
            $('#bttAdd').button('loading');
            Cart.Core.add(
                idProduct,
                quantity,
                function(result){
                    resultObj = JSON.parse(result);
                    if(resultObj['ok']){
                        Cart.Product.update();
                        $('#bttAdd').button('reset');
                    } else {
                        
                        //danger will robson
                    }
                }
            );
        },
        remove : function(idProduct,quantity){
            Cart.Core.remove(
                idProduct,
                quantity,
                function(result){
                    resultObj = JSON.parse(result);
                    if(resultObj['ok']){
                        Cart.Product.update();
                    } else {
                        
                        //danger will robson
                    }
                }
            );
        },
        update : function(){
            Cart.Core.update(
                function(result){
                    resultObj = JSON.parse(result);
                    if(resultObj['ok']){
                        
                    } else {
                        
                        //danger will robson
                    }
                }
            );
        }
    },
    CartPage : {
        removeItem : function(idProd){

            

            var qttCurr = $('#product-' + idProd + '-qtt').val();
            try{ qttCurr = !isNaN(qttCurr) ? parseFloat(qttCurr) : 0; }catch(err){ qttCurr = 0; }
            

            var qttToDel = qttCurr==0 ? 1 : qttCurr;
            

            Cart.Core.remove(
                idProd,
                qttToDel,
                function(result){
                    var idProd2 = idProd;
                    Cart.CartPage.fnUpdateItem(result,idProd2);
                }
            );
        },
        minusItem : function(idProd){

            

            var qttCurr = $('#product-' + idProd + '-qtt').val();
            try{ qttCurr = !isNaN(qttCurr) ? parseFloat(qttCurr) : 0; }catch(err){ qttCurr = 0; }
            

            var qttToDel = 1;
            

            Cart.Core.remove(
                idProd,
                qttToDel,
                function(result){
                    var idProd2 = idProd;
                    Cart.CartPage.fnUpdateItem(result,idProd2);
                }
            );
        },
        plusItem : function(idProd){
            

            var qttCurr = $('#product-' + idProd + '-qtt').val();
            try{ qttCurr = !isNaN(qttCurr) ? parseFloat(qttCurr) : 0; }catch(err){ qttCurr = 0; }
            

            var qttToAdd = 1;

            Cart.Core.add(
                idProd,
                qttToAdd,
                function(result){
                    var idProd2 = idProd;
                    Cart.CartPage.fnUpdateItem(result,idProd2);
                }
            );
        },
        changeQtt : function(idProd){

            

            var qttOrig = $('#product-' + idProd + '-qtt').attr('data-orig-value');
            try{ qttOrig = !isNaN(qttOrig) ? parseFloat(qttOrig) : 0; }catch(err){ qttOrig = 0; }
            

            var qttCurr = $('#product-' + idProd + '-qtt').val();
            try{ qttCurr = !isNaN(qttCurr) ? parseFloat(qttCurr) : 0; }catch(err){ qttCurr = 0; }
            

            var qttToDel = qttOrig;
            

            Cart.Core.remove(
                idProd,
                qttToDel,
                function(result){
                    var idProd2 = idProd;

                    var qttToAdd = qttCurr;
                    

                    Cart.Core.add(
                        idProd,
                        qttToAdd,
                        function(result){
                            Cart.CartPage.fnUpdateItem(result,idProd2);
                        }
                    );
                }
            );
        },
        fnUpdateItem : function(result, idProd){
            
            resultObj = JSON.parse(result);
            
            if(resultObj['ok']){
                
                var branch = resultObj['ok'][idProd];


                var qttActual = 0;
                if(
                    branch &&
                    branch['quantity'] != undefined &&
                    !isNaN(  branch['quantity']  )
                ){
                    
                    try{
                        qttActual = !isNaN(  branch['quantity']  ) ? parseInt(  resultObj['ok'][idProd]['quantity']  ) : 0;
                        
                    }catch(err){
                        qttActual = 0;
                        
                    }
                    
                    $('#product-' + idProd + '-qtt').val(qttActual);
                    $('#product-' + idProd + '-qtt').attr('data-orig-value',qttActual);
                    if( qttActual== 0 ){
                        $('#product-' + idProd + '-row').addClass('alert-danger');
                        $('#product-' + idProd + '-qtt').addClass('alert-danger');
                    }else if(qttActual < 0){
                        $('#product-' + idProd + '-row').fadeOut();
                    }else{
                        $('#product-' + idProd + '-row').removeClass('alert-danger');
                        $('#product-' + idProd + '-qtt').removeClass('alert-danger');
                    }
                }else{
                    
                    $('#product-' + idProd + '-row').fadeOut();
                }


                var valUnitPrice = 0;
                if(
                    branch &&
                    branch['value'] != undefined &&
                    !isNaN(  branch['value']  )
                ){
                    
                    try{
                        valUnitPrice = !isNaN(  branch['value']  ) ? parseFloat(  resultObj['ok'][idProd]['value']  ) : 0;
                        
                    }catch(err){
                        valUnitPrice = 0;
                        
                    }
                    
                    
                    var floatSubTotal = qttActual * valUnitPrice;
                    
                    $('#subtotal_'+idProd+' .--amount').html( formatBRL(floatSubTotal) );
                    $('#subtotal_'+idProd+' .--amount').attr( 'data-float', floatSubTotal );
                }else{
                    
                    // row has hidden above
                }


                
                var priceTotal = 0;
                var noPrice = true;
                $('[data-role="price-sub-total"]').each(
                    function(idx,el){
                        
                        
                        var currSubTot = $(el).find('[data-float]').attr('data-float');
                        
                        if(
                            currSubTot &&
                            !isNaN(  currSubTot  )
                        ){
                            
                            try{
                                priceTotal += !isNaN(  currSubTot  ) ? parseFloat(  currSubTot  ) : 0;
                                
                                noPrice = false;
                            }catch(err){
                                priceTotal += 0;
                                
                            }
                            
                        }else{
                            
                            priceTotal += 0;
                        }
                    }
                );


                if( !noPrice ){
                    $('#totalprice').html(  formatBRL(priceTotal)  );
                }else{
                    $('#totalprice').html('&mdash;');
                }

                Cart.Core.refreshCartButton(result);
            } else {
                
                //danger will robson
            }
        }
    },
    Core : {
        add    : function(idProduct,quantity,fnCallback){
            
            
            $.ajax(
                {
                    type: "POST",
                    data: jQuery.param(
                        {
                            id:idProduct,
                            quantity:quantity
                        }
                    ) ,
                    url:"/cart/add",
                    success: fnCallback
                }
            );
        },
        remove : function(idProduct,quantity,fnCallback){
            
            
            $.ajax(
                {
                    type: "POST",
                    data: jQuery.param(
                        {
                            id:idProduct,
                            quantity:quantity
                        }
                    ) ,
                    url:"/cart/remove",
                    success: fnCallback
                }
            );
        },
        update : function(fnCallback){
            $.ajax(
                {
                    url:"/cart/info",
                    success:function(result){
                    
                        resultObj = JSON.parse(result);
                    
                        if(resultObj['ok']){

                            Cart.Core.refreshCartButton(result);

                            if(
                                $('#canvasHasProducts').attr('data-product-id') &&
                                $('#canvasHasProducts').length>0
                            ){
                                // Update in product page item info canvas
                                var productQtt = 0;
                                for (key in resultObj['ok'] ){
                                    if( $('#canvasHasProducts').attr('data-product-id') ==  resultObj['ok'][key]['id']){
                                        productQtt+=parseInt(resultObj['ok'][key]['quantity']);
                                    }
                                }
                                if(productQtt > 0){
                                    $('#canvasHasProducts').show();
                                    $('#labelHasProductsNumber').html( productQtt ); // <!>
                                    $('#labelHasProductsSuffix').html( productQtt==1 ? 'item' : 'itens' );
                                }else{
                                    $('#canvasHasProducts').hide();
                                    $('#labelHasProductsNumber').html('&hellip; &hellip; &hellip;'); // <!>
                                    $('#labelHasProductsSuffix').html('&hellip; &hellip; &hellip;');
                                }
                            }
                        }
                        if(this.fnCallback){
                            this.fnCallback.apply(this,arguments);
                        }else{
                        }
                    }.bind( {fnCallback:fnCallback} )
                }
            );
        },
        refreshCartButton : function(result){
            if( $('#cartButton').length > 0 ){
                // Update in orçamento quantity badge
                var quantity = 0;
                for (key in resultObj['ok'] ){
                    var currQtt = parseInt(resultObj['ok'][key]['quantity'])
                    if(currQtt > 0){
                        quantity=quantity+currQtt;
                    }
                }
                if (quantity==0) {
                    $('#cartButton').hide();
                } else if (quantity==1) {
                    $('#cartButton .badge').html(quantity);
                    $('#cartButton').show();
                } else {
                    $('#cartButton .badge').html(quantity);
                    $('#cartButton').show();
                }
            }
        }
    }
}

var Menu = {
    initialize :function(){
        // Menu.show();
        var pathArray = window.location.pathname.split( '/' );
        if (pathArray[1] && pathArray[2] && pathArray[3]) {
            if (pathArray[1]=='produto') {
                if (pathArray[2]=='category') {
                    $('#category-00-'+pathArray[3]).addClass('active').find('span').removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-bottom');
                    $('.parent-00-'+pathArray[3]).removeClass('menu-collapsed');
                }
                if (pathArray[2]=='subcategory1') {
                    Menu.toggleParent(1,pathArray[3]);
                    $('#category-01-'+pathArray[3]).addClass('active').removeClass('menu-collapsed').find('span').removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-bottom');
                    $('.parent-01-'+pathArray[3]).removeClass('menu-collapsed');
                }
                if (pathArray[2]=='subcategory2') {
                    Menu.toggleParent(2,pathArray[3]);
                    $('#category-02-'+pathArray[3]).addClass('active').removeClass('menu-collapsed').find('span').removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-bottom');
                }
                if (pathArray[2]=='subcategory3') {
                    Menu.toggleParent(3,pathArray[3]);
                    $('#category-03-'+pathArray[3]).addClass('active').removeClass('menu-collapsed');

                }
            }
        }
    },
    toggleParent :function(lvl,categoryid){
        var parentElement = '#'+$('#category-0'+lvl+'-'+categoryid).attr('data-parent');
        for (var i = lvl - 1; i >= 0; i--) {
            $(parentElement).find('span').removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-bottom');
            var siblings = $(parentElement).attr('data-toggle-target');
            $(siblings).removeClass('menu-collapsed');
            parentElement = '#'+$(parentElement).attr('data-parent');
        }
    },
    hide : function(){
        $('#contentContainer').removeClass('col-sm-8').addClass('col-sm-12');
        $('#menu').hide();
    },
    show : function(){
        $('#contentContainer').removeClass('col-sm-12').addClass('col-sm-8');
        $('#menu').show();
    }
};

var Register = {
    previousSearchTerm : '',
    minCharsLookup : 7, // default: 7
    searchOnKey : function(){
        var txtQuery = $('#search-cnpj').val();
        if (
            txtQuery != '' &&
            txtQuery != Register.previousSearchTerm &&
            txtQuery.length >= Register.minCharsLookup
        ) {
            $('#page').val('1');
            Register.performSearch();
        } else {
            if ( txtQuery == '' ) {
                $("#searchResults .resultItems").empty();
                $("#searchResults").hide();
            }
        }
    },
    searchBtt : function(){
        var txtQuery = $('#search-cnpj').val();
        if (
            txtQuery != '' &&
            txtQuery.length >= Register.minCharsLookup
        ) {
            $('#page').val('1');
            Register.performSearch();
        } else {
            if ( txtQuery == '' ) {
                $("#searchResults .resultItems").empty();
                $("#searchResults").hide();
            }
        }
    },
    performSearch : function(){
        var query = $('#search-cnpj').val();
        var page = $('#page').val();
        $("#searchResults .resultItems").empty();
        $("#searchResults").hide();
        Register.previousSearchTerm = query;
        if (Register.globalTimeout != null) {
            clearTimeout(Register.globalTimeout);
        }
        Register.globalTimeout = setTimeout(function() {
            Register.globalTimeout = null;

            $.ajax({
                type: "GET",
                data: jQuery.param({ responseType: 'JSON',"query":query,"page":page}) ,
                // customResponse: userStatus,
                // label: elementLabel,
                url:'/cliente/search',
                beforeSend : function( jqXHR , settings){
                    $('#search-cnpj--loading--hide-addon').removeClass('input-group--hide-addon');
                },
                complete : function( jqXHR , textStatus){
                    $('#search-cnpj--loading--hide-addon').addClass('input-group--hide-addon');
                },
                success: function(result){
                    var resultObj = JSON.parse(result);
                
                    if(resultObj.ok){
                    
                        var model = $('.resultModel');
                        if (resultObj.ok.total_items==0) {
                            $('#noresults').show();
                            $("#searchResults").hide();
                        } else {
                            //console.warn(JSON.stringify(resultObj,null,'	'));
                            for (var i = 0; i < resultObj.ok.total_items; i++) {
                                if (resultObj.ok.items[i] && resultObj.ok.items[i].id) {
                                    model
                                        .clone()
                                        .attr('id','resultItem-'+resultObj.ok.items[i].id)
                                        .appendTo('#searchResults .resultItems')
                                        .show()
                                        .find('button')
                                        .confirmation(
                                            {
                                                rootSelector: '[data-toggle=confirmation]',
                                                onConfirm: function() {
                                                    Register.associate(this);
                                                },
                                                // other options
                                            }
                                        )
                                        .attr('data-id',resultObj.ok.items[i].id)
                                        .find('.clientName')
                                        .html(
                                            '<span style="font-weight:500">'+
                                            resultObj.ok.items[i].nome +
                                            '</span><br><span style="font-weight:300">'+
                                            'CNPJ - ' +
                                            resultObj.ok.items[i].cnpj_cpf+
                                            '</span><br><span style="font-weight:300">'+
                                            'Código Policom - ' +
                                            resultObj.ok.items[i].clienteUcode+
                                            '</span>'
                                        )
                                    ;
                                }
                            }
                            Paginator.init(
                                $('#searchResults [data-role-pagination="wrapper"]') ,
                                {
                                    'first' : resultObj.ok.first,
                                    'last' : resultObj.ok.last,
                                    'current' : resultObj.ok.current,
                                    'before' : resultObj.ok.before,
                                    'next' : resultObj.ok.next,
                                    'total_pages' : resultObj.ok.total_pages,
                                    'total_items' : resultObj.ok.total_items,
                                    'limit' : resultObj.ok.limit,
                                    'link' : [  'javascript:(function(){ Register.goToPage('  ,  '); })()'  ]
                                }
                            );
                            $('#noresults').hide();
                            $("#searchResults").show();
                        }
                    } else {
                    
                        //danger will robson
                    }
                }
            });
        }, 300);
    },
    goToPage : function( intPage ){
        $('#page').val( intPage );
        //Register.searchClient();
        Register.performSearch();
    },
    associate : function(element){
        var elementId = $(element).attr('data-id');
        $('#hiddenClientId').val(elementId);
        $('#associateForm').submit();
    },
    globalTimeout : null
}

var Paginator = {
    init : function( target , page ){

        /* SNNIPET
        page = {
            'first' : resultObj.ok.first,
            'last' : resultObj.ok.last,
            'current' : resultObj.ok.current,
            'before' : resultObj.ok.before,
            'next' : resultObj.ok.next,
            'total_pages' : resultObj.ok.total_pages,
            'total_items' : resultObj.ok.total_items,
            'limit' : resultObj.ok.limit,
            'link' : [  '?prefix='  ,  '&suffix'  ]
        };
        */

        var jqdTargetRoles = { // jqd stands for jQuery DOM
            'wrapper'   : target,
            'template'  : target.find(' [data-role-pagination="template"]'),
            'current'   : target.find(' [data-role-pagination="template"] [data-role-pagination="current"]'),
            'regular'   : target.find(' [data-role-pagination="template"] [data-role-pagination="regular"]'),
            'between'   : target.find(' [data-role-pagination="template"] [data-role-pagination="between"]'),
            'container' : target.find(' [data-role-pagination="container"]')
        };
        for( el in jqdTargetRoles){
        }

        jqdTargetRoles.container.html('');

        var offset = 3;
        var shownHellip = false;

        for(var index=page.first; index<=page.last; index++){
            var jqPageItem = null;
            if( (index > page.current - offset - 1 && index < page.current + offset + 1) || index == page.first || index == page.last ){
                if(page.current == index){
                    jqPageItem = jqdTargetRoles.current.clone().appendTo( jqdTargetRoles.container );
                    jqPageItem.find('a').html(index).attr(
                        'href',
                        page.link.join(index.toString())
                    );
                }else{
                    jqPageItem = jqdTargetRoles.regular.clone().appendTo( jqdTargetRoles.container );
                    jqPageItem.find('a').html(index).attr(
                        'href',
                        page.link.join(index.toString())
                    );
                    shownHellip = false;
                    
                }
            }else if( shownHellip == false ){
                jqPageItem = jqdTargetRoles.between.clone().appendTo( jqdTargetRoles.container );
                shownHellip = true;
                
            }
        }
    }
};

var Header = {
    update : function(){
        var users = 0;
        var msgs = 0;
        users=$.ajax({
            type: "GET",
            // customResponse: userStatus,
            // label: elementLabel,
            url:'/session/getPendingTasks',
            success: function(result){
                var resultObj = JSON.parse(result);
                if(resultObj.ok){;

                    users=parseInt(resultObj.ok.usuarios);
                    if(users){
                        $('#userstasks').text(users);
                        Header.accountWarn();
                    }
                    cliente=parseInt(resultObj.ok.clientes);
                    if(cliente){
                        $('#clientstasks').text(cliente);
                        Header.accountWarn();
                    }
                    msgs=parseInt(resultObj.ok.mensagens);
                    if(msgs){
                        $('#msgtasks').text(msgs);
                        Header.accountWarn();
                    }
                    invoices=parseInt(resultObj.ok.orcamentos);
                    if(invoices ){
                        $('#invoicestasks').text(invoices);
                        Header.accountWarn();
                    }
                } else {
                    
                    //danger will robson
                }
            }
        });
    },
    accountWarn : function(){
        var msgtasks =parseInt($('#msgtasks').text());
        var clientstasks =parseInt($('#clientstasks').text());
        var userstasks =parseInt($('#userstasks').text());
        var invoicestasks =parseInt($('#invoicestasks').text());
        var total = 0;
        if(!isNaN(msgtasks)){total+=msgtasks;}
        if(!isNaN(clientstasks)){total+=clientstasks;}
        if(!isNaN(userstasks)){total+=userstasks;}
        if(!isNaN(invoicestasks)){total+=invoicestasks;}
        $('#accountTasks').text(total);
    }
}

var Invoice = {
    search : function(){
        var url = '/orcamento/index';
        var userDate = $('#selectMonth').val();
        var userStatus = $('#selectStatus').val();
        var client = $('#clientHidden').val();
        var args = '?';
        if (userDate!='false' && userDate != null) {
            var dateSplit = userDate.split('-');
            var month = dateSplit[0];
            var year = dateSplit[1];
            var firstDay = new Date(parseInt(year), parseInt(month)-1, 1,0,0,1).toISOString().slice(0, 19).replace('T', ' ');;
            var lastDay = new Date(parseInt(year), parseInt(month) , 0,23,59,59).toISOString().slice(0, 19).replace('T', ' ');;
            args += 'period='+userDate+'&initdate='+firstDay+'&enddate='+lastDay;
        }
        if (userStatus !=null && userStatus!='total' ) {
            if (args!='?') {
                args+='&';
            }
            args += 'status='+userStatus;
        }
        if (client !=null && client !='' ) {
            if (args!='?') {
                args+='&';
            }
            args += 'client='+client;
        }
        window.location.href = url+args;
    },
    removeClient : function(){
        $('#clientHidden').attr('value',null);
        Invoice.search();
    },
    addClient : function( clientId ){
        $('#clientHidden').attr('value',clientId);
        Invoice.search();
    },
    initialize : function(){

        $('#selectMonth').val(getUrlParameter('period'));
        $('#selectStatus').val(getUrlParameter('status'));

        if(
            getUrlParameter('client') ||
            getUrlParameter('period') ||
            getUrlParameter('status')
        ){
            $('#bttClearFilter').show();
            $('#panel-orcamentos').addClass('panel-info').removeClass('panel-default');
        }else{
            $('#panel-orcamentos').addClass('panel-default').removeClass('panel-info');
        }
    }
}

var Acl = {
    save : function(element,role,action){
        var uri =  window.location.protocol+'//'+window.location.host+"/acl/save";
        var value = 0;
        $checked = $(element).attr('checked');
        //toggle value before saving.
        if($checked=='checked'){
            value=0;
            $(element).attr('checked',null);
        } else {
            value=1;
            $(element).attr('checked','checked');
        }
        $.ajax(
            {
                type: "POST",
                data: jQuery.param(
                    {
                        role:role,
                        action:action,
                        value:value
                    }
                ) ,
                url:uri,
                success: function(result){
                    if(result == 'ok'){

                        $('#user-status-alert').hide();
                    } else {
                        $('#user-status-alert').show();

                    }
                }
            }
        );
    }
}

var formatBRL = function(floatValue){
    var reToFixed2 = /^(\d+)(\.)(\d{2})$/;
    var intPart = floatValue.toFixed(2).replace(reToFixed2, '$1');
    var decPart = floatValue.toFixed(2).replace(reToFixed2, '$3')

    var strFormated = (
        intPart.split('')
            .reverse().join('') // rever integer part
            .replace(/(\d{3})/g, '$1.') // add mil dots
            .split('').reverse().join('') // revert to original
            .replace(/^\./, '') // revove unnecessary dot at begining if necessary
        + ',' + decPart
    );
    return strFormated;
};

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

var Product = {
    filter : function(){
        var $categoryFilters = $('input[type=checkbox].category-filter');
        var $selectmanufacturer = $('#selectmanufacturer');

        var getFiltersChecked = function() {
            var	filters = [];
            $( "input[type=checkbox].category-filter:checked" ).each(function(){
                filters.push($(this).attr('data-id'));
            });
            if (filters.length < 1) {
                return 0;
            }
            return filters;
        };

        var removeChildren = function(id) {

            $categoryFilters.each(function() {
                if (this.hasAttribute('data-parent') !== true || this.checked !== true) {
                    return ;
                }
                var parent = this.getAttribute('data-parent');
                if (parent === id) {
                    this.checked = false;
                    removeChildren(this.getAttribute('data-id'));
                }
            });
            execute();
        };

        var execute = function() {
            var manufacturer = '';
            var filters = getFiltersChecked();

            if ($selectmanufacturer.val()) {
                manufacturer = $selectmanufacturer.val();
            }
            var pathSplit = window.location.pathname.split('/');





            if (pathSplit.length >3 && pathSplit[1]=='produto' && pathSplit[2]=='category' && Number.isInteger(parseInt(pathSplit[3]))) {

                var path = '/'+pathSplit[1]+'/'+pathSplit[2]+'/'+pathSplit[3]+'/'+filters+'/'+manufacturer;
                location.href=path;
            }
        };

        // Item selected clicked
        $categoryFilters.click(function(e) {
            if (!this.checked) {
                removeChildren(this.getAttribute('data-id'));
            }
            execute();
        });

        $selectmanufacturer.change(function() {
            execute();
        });

        return {execute:execute};
    },
    limbo: function() {
        var form = Doc.getElementById('form-produto-limbo'), $form = $(form);
        if ($form.length < 1) {
            return ;
        }

        var anySelected = function(inputName) {
            var valid = false, elements = form.elements.namedItem(inputName);

            if (elements.length !== undefined) {
                for (var key in elements) {
                    if (elements.hasOwnProperty(key) !== true) {
                        continue;
                    }
                    if (elements[key].checked) {
                        valid = true;
                        break;
                    }
                }
            } else if (elements.checked) {
                valid = true;
            }

            return valid;
        };

        $form.submit(function() {
            if (anySelected('products[]') !== true) {
                Modal.alert({content: 'Selecione pelo menos 1 produto'});
            } else if (anySelected('categories[]') !== true) {
                Modal.alert({content: 'Selecione pelo menos 1 categoria'});
            } else {
                Modal.progress({title: 'Aguarde, estamos processando.', type: 'stripes-animated'});
                return true;
            }
            return false;
        });

        InputSearchInHtml('search_category');

        return {};
    },
    categoria: function() {
        var form = Doc.getElementById('form-admin-produto-categoria'), $form = $(form);
        if ($form.length < 1) {
            return ;
        }

        var isFormValid = function() {
            if (form.elements['nome'] === null || form.elements['nome'].value.length < 1) {
                Modal.alert({title: 'Erro', content: 'Preencha o nome da Categoria', type: 'danger'});
                return false;
            }

            var valid = false, categories = form.elements.namedItem('categories[]');
            for (var key in categories) {
                if (categories.hasOwnProperty(key) !== true) {
                    // just continue
                } else if (categories[key].checked) {
                    valid = true;
                    break;
                }
            }

            if (valid !== true) {
                Modal.alert({title: 'Erro', content: 'Selecione a categoria base', type: 'danger'});
            }
            return valid;
        };

        var popupRemoveCategoria = function(id) {
            Modal.confirmation({
                title: 'Excluir Categoria',
                content: 'Deseja realmente excluir está categoria?',
                btConfirm: 'Remover',
                btConfirmType: 'danger',
                btCancel: 'Cancelar',
                btCancelType: 'default',
                callbackConfirm: function($modal) {
                    $modal.modal('hide');
                    Modal.progress({title: 'Removendo categoria...'});
                    top.location.href = '/produto/categoriaExcluir/' + id;
                }
            });
        };

        var $btRemoveCategoria = $('.bt-remove-categoria');
        $btRemoveCategoria.click(function(e) {
            e.preventDefault();
            popupRemoveCategoria(this.getAttribute('data-id'));
        });

        $form.submit(function() {
            if (isFormValid()) {
                Modal.progress({title: 'Adicionando categoria...'});
                return true;
            }
            return false;
        });

        InputSearchInHtml('search_category');
    },
    edit: function () {
        var form = Doc.getElementById('form-produto-categories-edit'), $form = $(form);

        if ($form.length < 1) {
            return ;
        }

        InputSearchInHtml('search_category');
    }
};

var EmailAutomatico = {
    save: function () {
        var form = Doc.getElementById('form-email-automatico-save'), $form = $(form);
        if ($form.length < 1) {
            return ;
        }

        // Editor for body message
        Tinymce.simple('.tinymce-minimal');

        // Form fields
        var gatilho = form.elements.namedItem('gatilho'), $gatilho = $(gatilho);
        var $btAddInput = $('.bt-add-input');

        // Show trigger variables for body message
        var showVariables = function(id) {
            $('.gatilho-variables').each(function() {
                $(this).addClass('hidden');
            });

            $('#variables-' + id).removeClass('hidden');
        };

        /**
         * Load input data
         *
         * @param {String} targetId Input field id
         */
        var loadInputs = function(targetId) {
            var target = Doc.getElementById(targetId);
            if (target === null || target === undefined || target.value.length < 1) {
                return ;
            }

            // Parse string json
            var data = JSON.parse(target.value);
            if (typeof data !== 'object' || data.length === undefined || data.length === null || data.length < 1) {
                return ;
            }

            // Loop for array data
            for (var key in data) {
                if (!data.hasOwnProperty(key)) {
                    continue;
                }

                // Row
                var row = data[key];

                // Add input row
                var inputRow = addInput(targetId);

                // Set values to input row
                if (row.hasOwnProperty('name')) {
                    inputRow.name.value = row.name;
                }

                if (row.hasOwnProperty('email')) {
                    inputRow.email.value = row.email;
                }

                if (row.hasOwnProperty('estado') && row.estado.length > 0) {
                    inputRow.estado.value = row.estado;
                }
            }
        };

        /**
         * Show or hide inputs estado
         *
         * @param {Boolean} show
         */
        var showInputsEstado = function(show) {
            var inputCCEstados = form.elements.namedItem('cc_estado[]');
            var inputCCOEstados = form.elements.namedItem('cco_estado[]');

            var loopInputs = function(inputs) {
                if (inputs === null || inputs === undefined) {
                    // Silent
                } else if (inputs.constructor.name === 'HTMLSelectElement') {
                    if (show === true) {
                        $(inputs).removeClass('hidden');
                    } else {
                        $(inputs).addClass('hidden');
                    }
                } else if (inputs.constructor.name === 'RadioNodeList') {
                    for (var key in inputs) {
                        if (!inputs.hasOwnProperty(key)) {
                            continue;
                        }

                        if (show === true) {
                            $(inputs[key]).removeClass('hidden');
                        } else {
                            $(inputs[key]).addClass('hidden');
                        }
                    }
                }
            };

            loopInputs(inputCCEstados);
            loopInputs(inputCCOEstados);
        };

        /**
         * Add input
         *
         * @param {String} targetId
         */
        var addInput = function(targetId) {
            var targetName = targetId.replace('#', '');
            var blockInputs = Doc.getElementById('block-inputs-' + targetName);

            if (blockInputs === null || blockInputs === false) {
                
                return ;
            }

            /**
             * Get input text
             *
             * @param {Object} attributes
             * @returns {HTMLElement | HTMLInputElement | any}
             */
            var getInputText = function (attributes) {
                var input = Doc.createElement('input');
                input.type = 'text';

                if (typeof attributes !== 'object') {
                    return input;
                }

                for (var key in attributes) {
                    if (attributes.hasOwnProperty(key)) {
                        input.setAttribute(key, attributes[key]);
                    }
                }

                return input;
            };

            /**
             * Get Position
             *
             * @returns {string}
             */
            var getPosition = function () {
                var child = blockInputs.childNodes;
                return child.length.toString();
            };

            /**
             * Get button remove row
             * @param {String} position
             * @returns {HTMLElement | HTMLButtonElement | any}
             */
            var getButtonRemove = function (position) {
                var button = Doc.createElement('button');
                button.innerHTML = '<pan class="glyphicon glyphicon-remove"></pan>';
                button.type = 'button';
                button.className = 'btn bt-input-remove pull-right';
                button.setAttribute('data-target', targetName + '-' + position);

                button.addEventListener('click', buttonRemoveClick, false);

                return button;
            };

            /**
             * Button remove event
             *
             * @param e
             */
            var buttonRemoveClick = function(e) {
                e.preventDefault();
                var blockInput = '#block-input-' + this.getAttribute('data-target');
                $(blockInput).remove();
            };

            /**
             * Get options orçamento
             * @returns {HTMLElement | HTMLSelectElement | any}
             */
            var getOptionsOrcamento = function() {
                var selects = Doc.createElement('select');
                selects.name = targetName + '_estado[]';
                selects.className = 'form-control pull-left';
                selects.setAttribute('style', 'width: 15%');

                selects.add(new Option('Todos', '*'));
                selects.add(new Option('PO', 'PO'));
                selects.add(new Option('PA', 'PA'));
                selects.add(new Option('PR', 'PR'));
                selects.add(new Option('PC', 'PC'));

                return selects;
            };

            // Row position
            var position = getPosition();

            // Block Wrap inputs
            var blockWrap = Doc.createElement('div');
            blockWrap.className = 'form-group clearfix pt-10px';
            blockWrap.id = 'block-input-' + targetName + '-' + position;

            // Button Remove
            var buttonRemove = getButtonRemove(position);

            // Input Email
            var inputEmail = getInputText({
                'class': 'form-control pull-left mr-10px',
                'name': targetName + '_email[]',
                'style': 'width:35%',
                'placeholder': 'Email'
            });

            // Input Name
            var inputName = getInputText({
                'class': 'form-control pull-left mr-10px',
                'name': targetName + '_name[]',
                'style': 'width:35%',
                'placeholder': 'Nome do destinatário'
            });

            // Block Wrap
            blockWrap.appendChild(inputEmail);
            blockWrap.appendChild(inputName);

            // Add options list for Orçamento
            var selectEstado = getOptionsOrcamento();
            if ($gatilho.val() !== 'orcamento_criacao') {
                $(selectEstado).addClass('hidden');
            }

            // Input estado
            blockWrap.appendChild(selectEstado);

            // Button remove
            blockWrap.appendChild(buttonRemove);

            // Add block row
            blockInputs.appendChild(blockWrap);

            return {'email': inputEmail, 'name': inputName, 'estado': selectEstado};
        };

        /**
         * Gatilho change
         */
        $gatilho.change(function() {
            showVariables(this.value);
            showInputsEstado(this.value === 'orcamento_criacao');
        });

        /**
         * Input add click
         */
        $btAddInput.click(function (e) {
            e.preventDefault();

            if (!this.hasAttribute('data-target')) {
                return ;
            }

            var target = this.getAttribute('data-target');

            if (target.indexOf('#') !== 0) {
                
            }

            // Add input row
            addInput(this.getAttribute('data-target'));
        });

        // Show variables for trigger
        showVariables($gatilho.val());

        // Load CC data
        loadInputs('cc');

        // Load CCO data
        loadInputs('cco');

        // Show input estado
        showInputsEstado($gatilho.val() === 'orcamento_criacao');
    }
};

var Tinymce = {
    simple: function(selector) {
        tinyMCE.init({
            // General options
            selector: selector,
            theme : "simple"
        });
    },
    advanced: function(selector) {
        tinyMCE.init({
            // General options
            selector: selector,
            mode : "#TypeHere", theme : "advanced",
            plugins : "paste,autolink,lists,pagebreak,style,layer,table,save,advhr,advimage," +
                "advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace," +
                "print,contextmenu,directionality,fullscreen,noneditable,visualchars,nonbreaking," +
                "xhtmlxtras,template,wordcount,advlist,visualblocks",

            // Theme options
            theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
            theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
            theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
            theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : true,
            add_unload_trigger: false,

            theme_advanced_default_font_size : '10pt',
            theme_advanced_default_font_family : 'Verdana',
            theme_advanced_fonts : "Andale Mono=andale mono,monospace;Arial=arial,helvetica,sans-serif;Arial Black=arial black,sans-serif;Book Antiqua=book antiqua,palatino,serif;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier,monospace;Georgia=georgia,palatino,serif;Helvetica=helvetica,arial,sans-serif;Impact=impact,sans-serif;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco,monospace;Times New Roman=times new roman,times,serif;Trebuchet MS=trebuchet ms,geneva,sans-serif;Verdana=verdana,geneva,sans-serif;Webdings=webdings;Wingdings=wingdings,zapf dingbats",
            theme_advanced_font_sizes : '8pt,9pt,10pt,11pt,12pt,14pt,16pt,18pt,20pt,22pt,24pt,28pt,36pt',
            paste_retain_style_properties: 'font-size,font-family,color',
            paste_remove_styles_if_webkit: false,

            powerpaste_word_import: 'merge',
            powerpaste_html_import: 'clean',
            powerpaste_allow_local_images: false,

            // Example content CSS (should be your site CSS)
            // content_css : "css/content.css",

            // Drop lists for link/image/media/template dialogs
            template_external_list_url : "lists/template_list.js",
            external_link_list_url : "lists/link_list.js",
            external_image_list_url : "lists/image_list.js",
            media_external_list_url : "lists/media_list.js",

            // Style formats
            style_formats : [
                {title : 'Bold text', inline : 'b'},
                {title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
                {title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
                {title : 'Example 1', inline : 'span', classes : 'example1'},
                {title : 'Example 2', inline : 'span', classes : 'example2'},
                {title : 'Table styles'},
                {title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
            ],

            // Replace values for the template plugin
            template_replace_values : {
                username : "Some User",
                staffid : "991234"
            }
        });
    }
};

var checkAll = function(trigger,target){
    // trigger can be true, false or a selector string to/or checkbox object to get state from.
    // target must be a container selector string or collection
    var $targets = $(target).find('input[type=checkbox][data-may-check-all=true]');
    var state = (trigger===false ? false : trigger===true ||  $(trigger).prop('checked'));
    $targets.prop('checked', state);
};

/**
 * Search string in html
 *
 * @param {String} searchInputId
 * @example <div class="form-group has-feedback">
 *     	        <input type="search" name="search_category" id="search_category" class="form-control" placeholder="Buscar categoria" value=""/>
 *     	        <span id="searchInputId + _input_clear" class="glyphicon glyphicon-remove form-control-feedback"></span>
 *          </div>
 *          <div id="search_category_selected"></div>
 *          <div id="searchInputId + _list">
 *              <div class="searchInputId + _list_item">Text or Text with HTML</div>
 *          </div>
 * @constructor
 */
var InputSearchInHtml = function(searchInputId) {
    var searchListId = searchInputId + '_list', searchListItemClass = searchListId + '_item', searchInputClear = searchInputId + '_input_clear', searchSelected = searchInputId + '_selected';
    var $searchInput = $('#' + searchInputId),
        $searchInputClear = $('#' + searchInputClear),
        $searchSelected = $('#' + searchSelected),
        $searchList = $('#' + searchListId),
        $searchListItem = $('.' + searchListItemClass);
    var listSelected = [], listData = [];

    if ($searchInput.length !== 1)
        return;

    $searchInputClear.attr('style', 'cursor: pointer;pointer-events: auto');

    var removeAccents = function(str) {
        var accents    = 'ÀÁÂÃÄÅàáâãäåÒÓÔÕÕÖØòóôõöøÈÉÊËèéêëðÇçÐÌÍÎÏìíîïÙÚÛÜùúûüÑñŠšŸÿýŽž';
        var accentsOut = "AAAAAAaaaaaaOOOOOOOooooooEEEEeeeeeCcDIIIIiiiiUUUUuuuuNnSsYyyZz";
        str = str.split('');
        var strLen = str.length;
        var i, x;
        for (i = 0; i < strLen; i++) {
            if ((x = accents.indexOf(str[i])) !== -1) {
                str[i] = accentsOut[x];
            }
        }
        return str.join('');
    };

    var searchInputClearShow = function(show) {
        if (show) {
            $searchInputClear.removeClass('hidden');
        } else {
            $searchInputClear.addClass('hidden');
        }
    };

    var showSelectedData = function() {
        if (listSelected.length > 0) {
            $searchSelected.html('<strong>Selecionados</strong>: ' + listSelected.join(', '));
        } else {
            $searchSelected.html('');
        }
    };

    var addSelectedData = function(text) {
        listSelected.push(text);
        showSelectedData();
    };

    var removeSelectedData = function(text) {
        var removeKey = -1;
        for (var key in listSelected) {
            if (listSelected[key] === text) {
                removeKey = key;
                break;
            }
        }

        if (removeKey !== -1) {
            listSelected.splice(removeKey, 1);
        }
        showSelectedData();
    };

    var loadSelectedData = function() {
        for (var index in listData) {
            if (!listData.hasOwnProperty(index)) {
                continue;
            }
            if (listData[index].checked) {
                listSelected.push(listData[index].textSearchOriginal);
            }
        }
        showSelectedData();
    };

    var inputCheckboxClick = function(e) {
        var data = this.data;

        if (data.$inputCheckbox.prop('checked')) {
            addSelectedData(data.textSearchOriginal);
        } else {
            removeSelectedData(data.textSearchOriginal);
        }
    };

    var loadListData = function() {
        $searchListItem.each(function() {
            var dataSearch = this.getAttribute('data-search');
            if (dataSearch === null) {
                return ;
            }

            var data = {};

            data.element = this;
            data.$element = $(this);
            data.textSearchOriginal = dataSearch;
            data.textSearch = removeAccents(dataSearch.toLowerCase());
            data.$inputCheckbox = $(this.getElementsByTagName('input')[0]);
            data.checked = data.$inputCheckbox[0].checked;

            // Click event list item
            data.$inputCheckbox.click(inputCheckboxClick.bind({
                data: data
            }));

            listData.push(data);
        });

        loadSelectedData();
    };

    var executeSearchInput = function() {
        var searchText = $searchInput.val().toLowerCase();

        if (searchText.length < 1) {
            searchInputClearShow(false);
            $searchListItem.removeClass('hidden');
            return ;
        }

        for (var key in listData) {
            if (listData[key].textSearch.indexOf(searchText) === -1) {
                listData[key].$element.addClass('hidden');
            } else {
                listData[key].$element.removeClass('hidden');
            }
        }

        searchInputClearShow(true);
    };

    // Input text
    $searchInput.keyup(function(e) {
        e.preventDefault();
        executeSearchInput();
    });

    // Input clear
    $searchInputClear.click(function(e) {
        $searchInput.val('');
        executeSearchInput();
    });

    // Default
    searchInputClearShow(false);
    $searchInput.val('');
    $searchSelected.html('');
    loadListData();
};

var Captcha = {
    registerSubmitListener: function () {
        grecaptcha.ready(function () {
            $("[data-recaptcha-action]").submit(function (event) {
                event.preventDefault();

                var form = event.currentTarget;
                var action = $(form).data("recaptcha-action");
                grecaptcha.execute(
                    "6LecfrEZAAAAAOUgMvabvSolNPNytS3-34r9IuID",
                    { action }
                ).then(function (token) {
                    $(form).append(`<input type="hidden" name="grecaptcha_token" value="${token}">`);
                    $(form).append(`<input type="hidden" name="grecaptcha_action" value="${action}">`);

                    $(form).unbind('submit').submit();
                }).catch(function (error) {
                    console.log("reCAPTCHA Error", error);
                });
            });
        });
    },
};

$(document).ready(
    function () {

        Cart.Core.update();
        Form.reset();
        Header.update();
        Menu.initialize();
        Product.filter();
        Product.limbo();
        Product.categoria();
        Product.edit();
        EmailAutomatico.save();
        Captcha.registerSubmitListener();

        Tinymce.advanced('.tinymce');

        CreateTextClipboard('.copyclipboard');

        $("input").off('keypress',Form.reset).on('keypress',Form.reset);

        $('.category-filter-fabricante').on('click', function (evt) {
            evt.preventDefault();
    

            let categoria = evt.target.getAttribute('data-id');
            let sigla = evt.target.getAttribute('data-sigla');

            if(this.checked == false) {
                window.location.href = `/fabricante/index/${sigla}`;
            } else {
                window.location.href = `/fabricante/index/${sigla}/${categoria}`;
            }

           
        });

        $('.subcategories-filter-fabricante').on('click', function(evt) {
            evt.preventDefault();
            
            var attr = '';
            
            if(evt.target.getAttribute('checked'))
            {
                evt.target.setAttribute('checked', false);
            } else {   
                evt.target.setAttribute('checked', true);
            }
                
            let checkboxCategory = document.querySelector('.category-filter-fabricante:checked');
            let checkboxFilters = document.querySelectorAll('.subcategories-filter-fabricante:checked');

            let subcategorias = [...checkboxFilters].map(input => input.getAttribute('data-id'));
        

            window.location.href = `/fabricante/index/${checkboxCategory.getAttribute('data-sigla')}/${checkboxCategory.getAttribute('data-id')}/${subcategorias.join()}`;
        });

        // Fix css class on elements generated in backend
        $('[data-fix-element-classes=true]').find('input[type="text"],input[type="password"],select').addClass('form-control');

        // Setup lightbox
        lightbox.option({
            "albumLabel": "%1/%2",
            "wrapAround" : true,
            "fadeDuration" : 250,
            "imageFadeDuration" : 250,
            "resizeDuration" : 250
        });
    }
);