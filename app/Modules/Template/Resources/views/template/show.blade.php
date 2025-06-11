 <style>
     @media print {
         .printbtn {
             display: none;
         }
     }
 </style>


 {!! $template->text !!}

 <div style="margin-left: 20%">
     <button onclick="window.print();" class="printbtn"
         style="background-color: rgb(241, 146, 57); color: white; padding: 5px 15px; border:rgb(241, 146, 57); border-radius: 5px;"><b>Print</b></button>
 </div>
