<div id=fast_reply_block >
    
    <p style='padding-left: 55px'>=Fast Reply for Redmine=</p>
  <div style='margin: 20px;'>
   
   <textarea id=text></textarea>
  </div>  
</div>

<SCRIPT>
    
    
    tinymce.init({selector: "textarea", 
                plugins: "table, image, textcolor", 
                image_advtab: true,  
                toolbar: "undo redo | formatselect fontselect fontsizeselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | blockquote | image"
                });


    
    $(document).ready(function(){
          $('#fast_reply_block').css('width',($(document).width()-40) );
    });
</SCRIPT>