<?php
/**
 * Created by PhpStorm.
 * User: dekeeu
 * Date: 21/05/2017
 * Time: 20:37
 */
require_once "utils/commands.php";

function getUserGUI()
{
    $OUT = getHeader();
    $OUT.= "<div id='myContainer' class='container-fluid'>
        <div class='row'>
            <div class='btn-group'>
                <h1>Guest Book</h1>
                &nbsp;
                <button type='button' class='btn btn-danger' data-toggle='modal' data-target='#myModal'>Add review</button>
                <a href='logout.php'><button type='button' class='btn btn-success'>Log Out</button></a>
            </div>";
    $OUT.= "<script>

            function populateList(){
                $('.reviewEntry').remove();
                
                r = $.ajax({
                    url: 'action.php?a=all',
                    type: 'get'
                });
              
              r.done(function(response){
                       rvi = jQuery.parseJSON(response);
                       $.each(rvi, function(index, element){
                           $('#myContainer').append('&nbsp;');
                           $('#myContainer').append('<div class=\'row reviewEntry\'><div class=\'col-md-12 bg-success\'><h4>' + element['title'] + '(by ' + element['name'] + ') </h4> ' + element['comment'] + 
                            '</div></div>');
                       });
                   });
            }

            $(document).ready(function(){
               $('#formAdd').on('submit', function(ev){
                   ev.preventDefault();
                   
                   var \$form = $(this);
                   var \$inputs = \$form.find('input, textarea, button');
                   var serializedData = \$form.serialize();
                   \$inputs.prop('disabled', true);
                   
                   request = $.ajax({
                        url: 'action.php?a=add',
                        type: 'post',
                        data: serializedData
                   });
                   
                   request.done(function(response, textStatus, jqXHR){

                       // alert(response + ':::' + textStatus + ':::' + jqXHR);

                   });
                   
                   request.fail(function(jqXHR, textStatus, errorThrown){
                       alert('error:' + textStatus +',' + errorThrown);
                   });
                   
                   request.always(function(){
                       \$inputs.prop('disabled', false);
                   });
                   
                   populateList();
                   $('#myModal').modal('hide');
                   
               });
               
               populateList();
              
               
               
            });
        </script>";
    $OUT.= "<div id='myModal' class='modal fade' role='dialog'>
                    <div class='modal-dialog'>

                        <!-- Modal content-->
                        <div class='modal-content'>
                            
                            <!-- Modal header -->
                            
                            <div class='modal-header'>
                                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                <h4 class='modal-title'>Add a new review</h4>
                            </div>
                            
                            <!-- Modal body -->

                            <div class='modal-body'>

                                <form role='form' method='POST' id='formAdd' name='formAdd'>
                                    <div class='form-group'>
                                        <label for='inputTitle'>Title</label>
                                        <input name='title' type='text' class='form-control' id='title'>
                                    </div>

                                    <div class='form-group'>
                                        <label for='inputComment'>Comment</label>
                                        <textarea name='comment' class='form-control' rows='5' id='comment'></textarea>
                                    </div>

                                    <button id='submitBtn' name='Submit' value='submit' type='submit' class='btn btn-default'>Send</button>
                                </form>
                            </div>

                            <!-- Modal footer -->
                            
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                            </div>
                        </div>

                    </div>
                </div>
                </div>";
    $OUT.= "</div>";
    $OUT.= getFooter();
    return $OUT;
}

function getAdminGUI()
{
    $OUT = getHeader();
    $OUT.= "<div id='myContainer' class='container-fluid'>
        <div class='row'>
            <div class='btn-group'>
                <h1>Guest Book</h1>
                <div class='col-md-8 col-md-offset-2'>
                    <form action='#' method='get' id='searchForm' class='input-group'>
                        <div class='input-group-btn search-panel'>
                            <select name='search_by' id='search_by' class='btn btn-default dropdown-toggle' data-toggle='drodown'>
                                <option value='title'>Title</option>
                                <option value='author'>Author</option>                          
                            </select>                   
                        </div>
                        <input id='inputText' type='text' class='form-control' name='q' placeholder='Search term...'>
                        <span class='input-group-btn'>
                            <button class='btn btn-default' type='submit'>
                                <span class='glyphicon glyphicon-search'></span>
                            </button>
                        </span>
                    </form>
            </div>";
    
    $OUT.= "<script>
            var rvi;
            var currentPage = 1;
            
            function populateList(){
                $('.reviewEntry').remove();
                
                r = $.ajax({
                    url: 'action.php?a=all',
                    type: 'get'
                });
              
              r.done(function(response){
                       rvi = jQuery.parseJSON(response);
                       $.each(rvi, function(index, element){
                           $('#myContainer').append('&nbsp;');
                           $('#myContainer').append('<div class=\'row reviewEntry\'><div class=\'col-md-12 bg-success\'><h4>' + element['title'] + '(by ' + element['name'] + ') <span data-review-id=\'' + index + '\'class=\'glyphicon glyphicon-edit editBtn\' aria-hidden=\'true\'></span> <span data-review-id=\'' + index + '\'class=\'glyphicon glyphicon-remove deleteBtn\' aria-hidden=\'true\'></span></h4> ' + element['comment'] + 
                            '</div></div>');
                       });
                   });
            }

            $(document).ready(function(){
                
               $('#searchForm').on('submit', function(ev){
                   ev.preventDefault();
                   
                   var \$form = $(this);
                   var \$inputs = \$form.find('input, select');
                   var serializedData = \$form.serialize();
                   \$inputs.prop('disabled', true);
                   
                   request = $.ajax({
                        url: 'action.php?a=search&page=' + currentPage,
                        type: 'post',
                        data: serializedData
                   });
                   
                   request.done(function(response){
                      $('.reviewEntry').remove();
                      newRvi = jQuery.parseJSON(response);
                      $.each(newRvi, function(index, element){
                         $('#myContainer').append('&nbsp;');
                         $('#myContainer').append('<div class=\'row reviewEntry\'><div class=\'col-md-12 bg-success\'><h4>'
                            + element['title'] + '(by ' + element['name'] + ') <span data-review-id=\'' + index + '\'class=\'glyphicon glyphicon-edit editBtn\' aria-hidden=\'true\'></span> <span data-review-id=\''
                            + index + '\'class=\'glyphicon glyphicon-remove deleteBtn\' aria-hidden=\'true\'></span></h4> '
                            + element['comment'] 
                            + '</div></div>'); 
                      });
                   });
                   
                   request.always(function(){
                       \$inputs.prop('disabled', false);
                   });
                   
            });
               
               $('#formEdit').on('submit', function(ev){
                   ev.preventDefault();
                   
                   var \$form = $(this);
                   var \$inputs = \$form.find('input, textarea, button');
                   var serializedData = \$form.serialize();
                   \$inputs.prop('disabled', true);
                   
                   request = $.ajax({
                        url: 'action.php?a=edit',
                        type: 'post',
                        data: serializedData
                   });
                   
                   request.done(function(response, textStatus, jqXHR){

                       // alert(response + ':::' + textStatus + ':::' + jqXHR);

                   });
                   
                   request.fail(function(jqXHR, textStatus, errorThrown){
                       alert('error:' + textStatus +',' + errorThrown);
                   });
                   
                   request.always(function(){
                       \$inputs.prop('disabled', false);
                   });
                   
                   populateList();
                   $('#editModal').modal('hide');
                   
               });
               
               populateList();
               
               $(document).on('click', '.deleteBtn', function(ev){
                   ev.preventDefault();
                   indexedReviewID = $(this).attr('data-review-id');
                   reviewID = rvi[indexedReviewID]['rid'];
                   userID = rvi[indexedReviewID]['uid'];
                   
                   if(confirm('Are you sure you want to delete this ?')){
                       request = $.ajax({
                            url: 'action.php?a=delete',
                            type: 'post',
                            data: {user_id:userID, review_id:reviewID}
                       });
                       
                       request.done(function(response){
                           
                       });
                       
                       populateList();
                   }
                   
               });
             
               $(document).on('click', '.editBtn', function(ev){
                   indexedReviewID = $(this).attr('data-review-id');
                   reviewID = rvi[indexedReviewID]['rid'];
                   title = rvi[indexedReviewID]['title'];
                   comment = rvi[indexedReviewID]['comment'];
                   
                   $('#inputID').val(reviewID);
                   $('#title').val(title);
                   $('#comment').val(comment);
                   $('#editModal').modal('show');
                   
               });
               
               $('#previousPage').on('click', function(ev){
                  ev.preventDefault();
                  
                  if(currentPage == 1){
                      return;
                  }else{
                      currentPage = currentPage - 1;
                      $('#searchForm').submit();
                  }
                  
               });
               
               $('#nextPage').on('click', function(ev){
                  ev.preventDefault();
                  
                  currentPage = currentPage + 1;
                  $('#searchForm').submit();
                  
               });

              
               
               
            });
        </script>";

    $OUT.= "<div id='editModal' class='modal fade' role='dialog'>
                    <div class='modal-dialog'>

                        <!-- Modal content-->
                        <div class='modal-content'>
                            
                            <!-- Modal header -->
                            
                            <div class='modal-header'>
                                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                <h4 class='modal-title'>Edit Review</h4>
                            </div>
                            
                            <!-- Modal body -->

                            <div class='modal-body'>

                                <form role='form' method='POST' id='formEdit' name='formEdit'>
                                    <div class='form-group'>
                                        <label for='inputID'>ReviewID</label>
                                        <input name='id' id='inputID' type='text' class='form-control' value='' readonly='true'>
                                    </div>
                                    <div class='form-group'>
                                        <label for='inputTitle'>Title</label>
                                        <input name='title' type='text' class='form-control' id='title'>
                                    </div>

                                    <div class='form-group'>
                                        <label for='inputComment'>Comment</label>
                                        <textarea name='comment' class='form-control' rows='5' id='comment'></textarea>
                                    </div>

                                    <button id='submitBtn' name='Submit' value='submit' type='submit' class='btn btn-default'>Send</button>
                                </form>
                            </div>

                            <!-- Modal footer -->
                            
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                            </div>
                        </div>

                    </div>
                </div>
                </div>";

    $OUT.= "<ul class='pagination'>
                <li><a id='previousPage' href='#'>Previous Page</a></li>
                <li><a id='nextPage' href='#'>Next Page</a></li>
             </ul>";

    $OUT.= "</div>";
    $OUT.= getFooter();
    return $OUT;
}

function displayPage($meniu = "")
{
    echo $meniu;
}