<script setup>

</script>

<template>
    <!-- Contenedor principal con imagen de fondo y sombra oscura -->
    <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <!-- Imagen de fondo -->
        <img 
                    src="/img/FondoLogin.jpg" 
                    alt="Background" 
                    class="absolute top-0 left-0 w-full h-full object-cover"
                >

        <!-- Sombra oscura encima de la imagen de fondo -->
        <div class="absolute inset-0 bg-black opacity-50"></div>

        <!-- Contenedor principal del contenido -->
        <div class="relative w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg z-10">
            <slot />
        </div>
    </div>
</template>
