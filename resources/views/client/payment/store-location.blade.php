<!-- Store Location Modal - Include this in your layout or any page that needs it -->
<div id="locationModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeLocation()"></div>
        
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Store Location</h2>
                <button onclick="closeLocation()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="p-6">
                <div class="bg-gray-100 rounded-xl p-6 mb-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">2Dine-In Restaurant</h3>
                    <div class="space-y-2 text-gray-600">
                        <p class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-[#ea5a47] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>San Juan Bautista, Goa, Camarines Sur</span>
                        </p>
                        <p class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-[#ea5a47] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>(054) 123 4567</span>
                        </p>
                        <p class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-[#ea5a47] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Open Daily: 10:00 AM - 10:00 PM</span>
                        </p>
                    </div>
                </div>
                
                <!-- Embedded Google Maps -->
                <div class="rounded-xl overflow-hidden h-96">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3881.2832050812537!2d123.489915!3d13.698056!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a2f1f1f1f1f1f1%3A0x0!2sSan%20Juan%20Bautista%2C%20Goa%2C%20Camarines%20Sur!5e0!3m2!1sen!2sph!4v1234567890"
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
                
                <div class="mt-4 flex gap-3">
                    <a href="https://maps.google.com/?q=San+Juan+Bautista+Goa+Camarines+Sur" 
                       target="_blank"
                       class="flex-1 px-4 py-2 bg-[#ea5a47] text-white rounded-lg hover:bg-[#c53030] transition-all text-center">
                        Open in Google Maps
                    </a>
                    <a href="https://waze.com/ul?q=San+Juan+Bautista+Goa+Camarines+Sur" 
                       target="_blank"
                       class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all text-center">
                        Open in Waze
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showLocation() {
    document.getElementById('locationModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLocation() {
    document.getElementById('locationModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>