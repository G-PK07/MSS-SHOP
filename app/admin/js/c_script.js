$(function(){
    var c_provinceObject = $('#c_province');
    var c_amphureObject = $('#c_amphure');
    var c_districtObject = $('#c_tambon');
    var c_zipcodeObject = $('#c_zipcode');

    // on change province
    c_provinceObject.on('change', function(){
        var provinceId = $(this).val();
       // alert("--->"+provinceId);

       c_amphureObject.html('<option value="">เลือกอำเภอ</option>');
       c_districtObject.html('<option value="">เลือกตำบล</option>');

        $.get('get_amphure.php?province_id=' + provinceId, function(data){

           // alert("===>"+ data);
            var result = JSON.parse(data);
            
            $.each(result, function(index, item){
                //alert(item.name_th);
                c_amphureObject.append(
                    $('<option></option>').val(item.id).html(item.name_th)
                );
            });
        });
    });

    // on change amphure
    c_amphureObject.on('change', function(){
        var amphureId = $(this).val();

        c_districtObject.html('<option value="">เลือกตำบล</option>');
        
        $.get('get_tambons.php?amphure_id=' + amphureId, function(data){
            var result = JSON.parse(data);
            $.each(result, function(index, item){
                c_districtObject.append(
                    $('<option></option>').val(item.id).html(item.name_th)
                );
                
            });
        });
    });
    // on change tambon
    c_districtObject.on('change', function(){
        var tambonId = $(this).val();

        $.get('get_zipcode.php?tambon_id=' + tambonId, function(data){
           // alert(data);
            var result = JSON.parse(data);
             $.each(result, function(index, item){
                c_zipcodeObject.append(
                      // $("input:text").val(item.zip_code)
                       $("#c_zipcode").val(item.zip_code)

                 );
                
             });
        });
    });
});