<?php

Route::resource("/api/alimento","AlimentoController"); //ruta,no distingue mayusculas de minisculas---segunfo nombre del controlador
Route::resource("/api/categoria-alimento","CategoriaAlimentoController");
Route::resource("/api/alimento-comentario","AlimentoComentarioController");
Route::resource("/api/datos-cafeteria","DatoCafeteriaController");
Route::resource("/api/orden","OrdenController");
Route::resource("/api/pedido","PedidoController");
Route::resource("/api/servicio-comentario","ServicioComentarioController");
Route::get("/api/alimento/categoria-alimento/{id}", "AlimentoController@GetAlimentosPorCategoriaAlimento");
Route::get("/api/servicio-comentario/usuario/{id}", "ServicioComentarioController@GetServiciosComentariosPorUsuario");
Route::get("/api/alimento-comentario/usuario/{id}", "AlimentoComentarioController@GetAlimentosComentariosPorUsuario");
Route::get("/api/alimento-comentario/alimento/{id}", "AlimentoComentarioController@GetAlimentosComentariosPorAlimento");
Route::get("/api/pedido/orden/{id}", "PedidoController@GetPedidosPorOrdenes");
Route::get("/api/pedido/alimento/{id}", "PedidoController@GetPedidosPorAlimentos");
Route::get("/api/orden/usuario/{id}", "OrdenController@GetOrdenesPorUsuario");
Route::post("/api/alimento/imagen", "AlimentoController@SubirImagen");
Route::get("/api/alimento/imagen-mostrar/{nombre_imagen}", "AlimentoController@GetImagen");
Route::resource("/api/usuario","UsuarioController");
Route::post("/api/usuario/imagen", "UsuarioController@SubirImagen");
Route::get("/api/usuario/imagen-mostrar/{nombre_imagen}", "UsuarioController@GetImagen");
Route::post("/api/usuario/sesion", "UsuarioController@sesion");
