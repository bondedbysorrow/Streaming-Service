<div 
    x-show="open" 
    x-cloak
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed z-50 inset-0 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
>
    <div class="flex items-end sm:items-center justify-center min-h-full pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Fondo oscuro -->
        <div 
            x-show="open"
            x-transition.opacity
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            aria-hidden="true"
            @click="cerrarModal()"
        ></div>

        <!-- Contenido del modal -->
        <div 
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
        >
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Confirmar compra
                        </h3>
                        
                        <div class="mt-6 flex items-center space-x-4">
                            <img 
                                :src="imagenProducto" 
                                class="h-20 w-20 rounded-lg object-cover border border-gray-200"
                                :alt="buyingProduct.nombre"
                            >
                            <div>
                                <h4 class="font-bold text-gray-800" x-text="buyingProduct.nombre"></h4>
                                <p class="text-sm text-gray-500" x-text="buyingProduct.descripcion_corta"></p>
                                <div class="mt-2">
                                    <span class="text-xl font-bold text-purple-600" x-text="'$' + precioFormateado"></span>
                                    <span class="text-xs text-gray-500 ml-1">c/u</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                            <div class="mt-1 flex items-center space-x-2">
                                <button 
                                    @click="cantidad > 1 ? cantidad-- : null" 
                                    class="px-3 py-1 border rounded-md bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors"
                                    :disabled="cantidad <= 1"
                                    :class="{ 'opacity-50 cursor-not-allowed': cantidad <= 1 }"
                                >-</button>
                                <span x-text="cantidad" class="px-4 py-1 border rounded-md text-center w-12"></span>
                                <button 
                                    @click="cantidad++" 
                                    class="px-3 py-1 border rounded-md bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors"
                                >+</button>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-3 bg-gray-50 rounded-md">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-700">Total:</span>
                                <span class="text-lg font-bold text-purple-600" x-text="'$' + totalFormateado"></span>
                            </div>
                        </div>
                        
                        <div x-show="error" class="mt-4 p-3 bg-red-50 text-red-600 rounded-md text-sm" x-text="error"></div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button 
                    @click="comprarAhora()"
                    type="button"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                    :disabled="loading"
                >
                    <span x-show="!loading">Comprar ahora</span>
                    <span x-show="loading" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Procesando...
                    </span>
                </button>
                <button 
                    @click="cerrarModal()"
                    type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                    :disabled="loading"
                >
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>