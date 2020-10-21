   $('#screen_size').change(function() {
      
                var size = $(this).val();
                if(size==1){
                    
                    //development mode :  Remove all extra classes
                    $('.mobile-image').removeClass('mobile-sony');
                    $('.mobile-image').removeClass('mobile-htcone');
                    $('.mobile-image').removeClass('mobile-samsung-y-2');
                    
                    $('#form-title h2').removeClass('mobile-samsung-h2');
                    $('#form-title img').removeClass('mobile-samsung-img');
                    $('#form-title h2').removeClass('mobile-sony-h2');
                    $('#form-title img').removeClass('mobile-sony-img');
                    $('#form-title h2').removeClass('mobile-htcone-h2');
                    $('#form-title img').removeClass('mobile-htcone-img');
                    $('.title-img').css('top','0px');
                    
                    $('#form-builder').attr('style','')
                }
                else if(size==2)//for sony
                {
                    //sony :  Remove all extra classes
                    $('.mobile-image').removeClass('mobile-htcone');
                    $('.mobile-image').removeClass('mobile-samsung-y-2');
                    
                    $('#form-title h2').removeClass('mobile-samsung-h2');
                    $('#form-title img').removeClass('mobile-samsung-img');
                    $('#form-title h2').removeClass('mobile-htcone-h2');
                    $('#form-title img').removeClass('mobile-htcone-img');
                    
                    //add sony classes
                    $('.mobile-image').addClass('mobile-sony');
                    $('#form-title h2').addClass('mobile-sony-h2');
                    $('#form-title img').addClass('mobile-sony-img');
                    
                    $('#form-builder').attr('style','width:292px;position: relative;top: 19px;left: 198px;height:518px;overflow-y: auto;')
                }
                else if(size==3)//for htc
                {
                    //htc :  Remove all extra classes
                    $('.mobile-image').removeClass('mobile-sony');
                    $('.mobile-image').removeClass('mobile-samsung-y-2');
                    
                    $('#form-title h2').removeClass('mobile-sony-h2');
                    $('#form-title img').removeClass('mobile-sony-img');
                    $('#form-title h2').removeClass('mobile-samsung-h2');
                    $('#form-title img').removeClass('mobile-samsung-img');
                    
                    //add htc classes
                    $('.mobile-image').addClass('mobile-htcone');
                    $('#form-title h2').addClass('mobile-htcone-h2');
                    $('#form-title img').addClass('mobile-htcone-img');
                    
                    $('#form-builder').attr('style','width:276px;position: relative;top: 23px;left: 208px;height:489px;overflow-y: auto;')
                }
                else if(size==4)//for Samsung Yound 2
                {
                     //alert('zahidaa');
                    //samsung :  Remove all extra classes
                    $('.mobile-image').removeClass('mobile-sony');
                    $('.mobile-image').removeClass('mobile-htcone');
                    
                    $('#form-title h2').removeClass('mobile-sony-h2');
                    $('#form-title img').removeClass('mobile-sony-img');
                    $('#form-title h2').removeClass('mobile-htcone-h2');
                    $('#form-title img').removeClass('mobile-htcone-img');
                    
                    
                    //add samsung classes
                    $('.mobile-image').addClass('mobile-samsung-y-2');
                    $('#form-title h2').addClass('mobile-samsung-h2');
                    $('#form-title img').addClass('mobile-samsung-img');
                    $('.title-img').css('top','-4px');
                    
                    $('#form-builder').attr('style','width:228px;position: relative;top: 95px;left: 232px;height:333px;overflow-y: auto;font-size:80%;')
                }
                
        });