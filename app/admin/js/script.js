$(function(){
    var provinceObject = $('#province');
    var amphureObject = $('#amphure');
    var districtObject = $('#tambon');
    var zipcodeObject = $('#zipcode');

    // on change province
    provinceObject.on('change', function(){
        var provinceId = $(this).val();
       // alert("--->"+provinceId);

        amphureObject.html('<option value="">เลือกอำเภอ</option>');
        districtObject.html('<option value="">เลือกตำบล</option>');

        $.get('get_amphure.php?province_id=' + provinceId, function(data){

           // alert("===>"+ data);
            var result = JSON.parse(data);
            
            $.each(result, function(index, item){
                //alert(item.name_th);
                amphureObject.append(
                    $('<option></option>').val(item.id).html(item.name_th)
                );
            });
        });
    });

    // on change amphure
    amphureObject.on('change', function(){
        var amphureId = $(this).val();

        districtObject.html('<option value="">เลือกตำบล</option>');
        
        $.get('get_tambons.php?amphure_id=' + amphureId, function(data){
            var result = JSON.parse(data);
            $.each(result, function(index, item){
                districtObject.append(
                    $('<option></option>').val(item.id).html(item.name_th)
                );
                
            });
        });
    });
    // on change tambon
    districtObject.on('change', function(){
        var tambonId = $(this).val();

        $.get('get_zipcode.php?tambon_id=' + tambonId, function(data){
           // alert(data);
            var result = JSON.parse(data);
             $.each(result, function(index, item){
                 zipcodeObject.append(
                      // $("input:text").val(item.zip_code)
                       $("#zipcode").val(item.zip_code)

                 );
                
             });
        });
    });
});