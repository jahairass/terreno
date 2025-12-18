@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f2f2f2; /* gris claro */
    }
    .title-bar {
        background: #000;
        color: #fff;
        padding: 15px;
        border-radius: 6px;
    }
    .property-card {
        background: #ffffff;
        border: 1px solid #dcdcdc;
        border-radius: 10px;
        overflow: hidden;
        transition: 0.3s;
    }
    .property-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.18);
    }
    .property-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .property-title {
        color: #000; 
        font-weight: bold;
        font-size: 1.3rem;
    }
    .btn-elegant {
        background: #f1c40f; /* amarillo elegante */
        color: #000;
        font-weight: 600;
        border: none;
        transition: 0.3s;
    }
    .btn-elegant:hover {
        background: #d4ac0d;
    }
</style>

<div class="container">

    <div class="title-bar mb-4">
        <h2 class="m-0">Clasificación de Casas</h2>
    </div>

    <div class="row">

        <!-- Casa Premium -->
        <div class="col-md-4 mb-4">
            <div class="property-card">
                <img src="images/casapremium.jpg" alt="Casa Premium">
                <div class="p-3">
                    <h4 class="property-title">PREMIUM </h4>
                    <p>
                        Vivienda de alta calidad con acabados de lujo, áreas amplias,
                        seguridad, jardines, amenidades y ubicación privilegiada.
                    </p>
                    <button class="btn btn-elegant w-100">Ver detalles</button>
                </div>
            </div>
        </div>

        <!-- Casa Media -->
        <div class="col-md-4 mb-4">
            <div class="property-card">
                <img src="images/casamedium.jpg" alt="Casa Media">
                <div class="p-3">
                    <h4 class="property-title">MEDIA</h4>
                    <p>
                        Vivienda cómoda con espacios funcionales, acabados de calidad,
                        servicios completos y buena conectividad urbana.
                    </p>
                    <button class="btn btn-elegant w-100">Ver detalles</button>
                </div>
            </div>
        </div>

        <!-- Casa Básica -->
        <div class="col-md-4 mb-4">
            <div class="property-card">
                <img src="images/basica1.jpg" alt="Casa Basica">
                <div class="p-3">
                    <h4 class="property-title">BÁSICA</h4>
                    <p>
                        Vivienda accesible para familias que buscan economía,
                        funcionalidad y servicios esenciales para vivir cómodamente.
                    </p>
                    <button class="btn btn-elegant w-100">Ver detalles</button>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection