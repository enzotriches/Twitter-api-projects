<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <!--<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>-->
  <title>Twits</title>
  <link rel="stylesheet" href="../Assets/css/index.css">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous">
  </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="../Assets/js/AjaxRequest.js"></script>  
</head>
<body class="bg-light">
  
<!--  ALL  -->
  <div class="container-fluid bg-transparent p-1">
    <div class="row">
     
      <!--  POST  -->
      <header class="col-12 col-lg-4 mt-lg-4 bg-white rounded shadow justify-content-bottom text-center border-lg border-dark p-4 mt-1 mb-4" style="max-height: 260px; ">
        <form action="" class="form mt-lg-4" id="form-tweet">
          <fieldset>
            <legend>
              <span class=" h3">
               Que palavra tu mais fala no Twitter?
              </span>
            </legend>

          <div class="form-label-group">

            <div class="row">
              <div class="col-12 col-lg-8">
                 <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-primary text-light h2 rounded" id="arroma"><b>@</b></span>
                  </div>
                  <input type="text" class="form-control p-1 border-primary rounded" placeholder="Username" id="who" aria-label="Username" aria-describedby="arroba">
                </div>
              </div>
              <div class="col-12 col-lg-4">
                <input type="submit" class="btn btn-primary col-12" value="Ok!">
              </div>
            </div>
          </div>
          </fieldset>
        </form>
      </header>
      <section class="col-12 col-lg-8 mt-lg-4">
        
         
          <!--  GET   -->
          <div class="container bg-light border-primary p-4 mt-1">
            <div class="container-fluid bg-transparent mb-3" id="about" style="transition: 2s;">
            
            </div>
            <div class="container-fluid bg-transparent mb-1" id="response" style="transition: 2s;">
            </div>
         </div>
       </section>
    </div>
  </div>
</body>
</html>