
    // Variable to store your files
    var files,form_id,loader_id,content_id;

    $(document).ready(function(){
        form_id=$('#upload_ajax_form_id').val();
        loader_id=$('#upload_ajax_loader_id').val();
        content1_id=$('#upload_ajax_content1_id').val();
        content2_id=$('#upload_ajax_content2_id').val();
        table_id=$('#upload_ajax_table_id').val();
    });
    

    // Grab the files and set them to our variable
    function prepareUpload(event)
    {        
        files = event.target.files;        
        if(files[0].type != 'image/png' && files[0].type != 'image/jpg' && files[0].type != 'image/gif' && files[0].type != 'image/jpeg' ) 
        {
            alert("The file does not match png, jpg or gif");
            $(':file').val('');
        }        
    }   

    // Catch the form submit and upload the files
    function uploadFiles(event)
    {        
        event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening

        // START A LOADING SPINNER HERE

        // Create a formdata object and add the files
        var data = new FormData();
        $.each(files, function(key, value)
        {
            data.append(key, value);
        });
                

        $.ajax({
            url: $('#'+form_id).attr('action')+'?files',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            beforeSend:function(){
                $('#'+loader_id).show();
            },            
            success: function(data, textStatus, jqXHR)
            {
                if(typeof data.error === 'undefined')
                {
                    // Success so call function to process the form
                    submitForm(event, data);
                }
                else
                {
                    // Handle errors here
                    console.log('ERRORS: ' + data.error);
                    noty({text: 'gagal mengupload file', layout: 'topRight', type: 'error',timeout:5000});
                }
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                // Handle errors here
                console.log('ERRORS1: ' + textStatus);
                noty({text: 'gagal mengupload file', layout: 'topRight', type: 'error',timeout:5000});
                $('#'+loader_id).hide();
                // STOP LOADING SPINNER
            }
        });
    }

    function submitForm(event, data)
    {
        // Create a jQuery object from the form
        $form = $(event.target);
        
        // Serialize the form data
        var formData = $form.serialize();
        
        // You should sterilise the file names
        $.each(data.files, function(key, value)
        {
            formData = formData + '&filename=' + value;
        });
        

        $.ajax({
            url: $('#'+form_id).attr('action'),
            type: 'POST',
            data: formData,
            cache: false,
            dataType: 'json',
            success: function(data, textStatus, jqXHR)
            {
                if(typeof data.error === 'undefined')
                {
                    // Success so call function to process the form                    
                    $('#'+content1_id).html(data.page_content1);

                    if(content2_id!='')
                    {
                        $('#'+content2_id).html(data.page_content2);
                    }

                    var dataTable_id=(typeof(table_id)=='undefined'?'data-table-jq':table_id);

                    oTable = $('#'+dataTable_id).dataTable({
                                "oLanguage": {
                                "sSearch": "Search :"
                                },
                                "aoColumnDefs": [
                                    {
                                        'bSortable': false,
                                        'aTargets': [0]
                                    } //disables sorting for column one
                                ],
                                'iDisplayLength': 10,
                                "sPaginationType": "full_numbers"
                            });

                    noty({text: 'berhasil mengupload file', layout: 'topRight', type: 'success',timeout:5000});
                }
                else
                {
                    // Handle errors here
                    console.log('ERRORS: ' + data.error);
                    noty({text: data.error, layout: 'topRight', type: 'error',timeout:5000});
                }
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                // Handle errors here
                console.log('ERRORS2: ' + textStatus);
                noty({text: 'gagal mengupload file', layout: 'topRight', type: 'error',timeout:5000});
                $('#'+loader_id).hide();
            },
            complete:function(){                
                $('#'+loader_id).hide();
            }            
        });
    }
