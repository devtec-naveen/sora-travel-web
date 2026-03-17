<main class="bg-slate-50 min-h-[800px]">
   <div class="bg-slate-100 py-3 border-b border-slate-200/60">
      <div class="container">
         <div class="flex items-center gap-2">
            <a href="index.php"
               class="font-normal text-sm text-slate-500 hover:text-blue-600 transition-colors">Home</a>
            <i data-tabler="chevron-right" class="text-slate-500" data-size="14" data-stroke="2"></i>
            <span class="font-semibold text-sm text-slate-500">Hotel Booking Details</span>
         </div>
      </div>
   </div>
   <div class="py-6 lg:py-12">
      <div class="container">
         <div class="flex flex-col gap-6">
            <!-- Header Section -->
            <div class="flex flex-col gap-4 md:gap-6">
               <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                  <h1 class="text-2xl md:text-3xl font-semibold text-slate-950 leading-9">Booking Detail</h1>
                  <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                     <button class="btn btn-secondary w-full sm:w-auto" >
                     Download Voucher
                     </button>
                     <button class="btn btn-red w-full sm:w-auto" >
                        Cancel
                     </button>
                  </div>
               </div>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col gap-6 md:gap-9">
               <!-- Hotel Details and Pricing -->
               <div class="flex flex-col lg:flex-row gap-4 md:gap-6 lg:gap-9">
                  <!-- Hotel Details Card -->
                  <div class="flex-1 card p-4 md:p-5">
                     <div class="flex flex-col gap-4 md:gap-6">
                        <!-- Booking ID -->
                        <div class="text-sm md:text-base font-normal text-slate-500 leading-6">#23523465346</div>
                        
                        <!-- Hotel Image and Info -->
                        <div class="flex flex-col sm:flex-row items-start gap-4 md:gap-5">
                           <img src="images/hotel-1.jpg" alt="Budget Inn Antalya" class="w-full sm:w-[237px] h-[150px] sm:h-[134px] rounded-xl object-cover shrink-0" />
                           <div class="flex-1 flex flex-col gap-2.5">
                              <div class="flex flex-col gap-2.5">
                                 <div class="flex flex-col gap-1">
                                    <div class="text-lg md:text-xl font-semibold text-slate-950 leading-7 md:leading-8">Budget Inn Antalya</div>
                                    <div class="flex items-center gap-1 text-slate-500">
                                       <i data-tabler="map-pin" class="w-5 h-5" data-size="20"></i>
                                       <span class="text-sm font-normal leading-5">Antalya, Turkey</span>
                                    </div>
                                 </div>
                                 <div class="flex items-center gap-1">
                                    <span class="text-sm font-semibold text-slate-950 leading-5">Mon, Feb 16, 2026</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

                  <!-- Pricing Summary Card -->
                  <div class="w-full lg:w-[350px] card flex flex-col">
                     <div class="p-4 md:p-5 border-b border-slate-200">
                        <h2 class="text-lg md:text-xl font-semibold text-slate-950 leading-7 md:leading-8">Pricing Summary</h2>
                     </div>
                     <div class="flex-1 p-4 md:p-5 flex flex-col gap-4 md:gap-6">
                        <div class="flex flex-col gap-3 md:gap-3.5">
                           <div class="flex flex-col gap-3 md:gap-3.5">
                              <div class="flex justify-between items-center gap-2">
                                 <div class="text-xs md:text-sm font-normal text-slate-950 leading-5">Room (€45 × 3 nights)</div>
                                 <div class="text-xs md:text-sm font-normal text-slate-500 leading-5 whitespace-nowrap">€148.75</div>
                              </div>
                              <div class="flex justify-between items-center gap-2">
                                 <div class="text-xs md:text-sm font-normal text-slate-950 leading-5">Taxes & Fees</div>
                                 <div class="text-xs md:text-sm font-normal text-slate-500 leading-5 whitespace-nowrap">€148.75</div>
                              </div>
                           </div>
                           <div class="h-px bg-slate-200"></div>
                           <div class="flex justify-between items-center gap-2">
                              <div class="text-base md:text-lg font-semibold text-slate-950 leading-6 md:leading-7">Total</div>
                              <div class="text-base md:text-lg font-semibold text-slate-950 leading-6 md:leading-7 whitespace-nowrap">€190</div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

               <!-- Booking Details Card -->
               <div class="card flex flex-col">
                  <div class="p-4 md:p-5 border-b border-slate-200">
                     <h2 class="text-lg md:text-xl font-semibold text-slate-950 leading-7 md:leading-8">Booking Details</h2>
                  </div>
                  <div class="p-4 md:p-5 flex flex-col gap-4 md:gap-6">
                     <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                        <div class="min-w-[150px] flex flex-col gap-1">
                           <div class="text-xs md:text-sm font-normal text-slate-500 leading-5">Check-in</div>
                           <div class="text-sm md:text-base font-semibold text-slate-950 leading-6">Feb 13, 2026</div>
                        </div>
                        <div class="min-w-[150px] flex flex-col gap-1">
                           <div class="text-xs md:text-sm font-normal text-slate-500 leading-5">Check-out</div>
                           <div class="text-sm md:text-base font-semibold text-slate-950 leading-6">Feb 16, 2026</div>
                        </div>
                        <div class="min-w-[150px] flex flex-col gap-1">
                           <div class="text-xs md:text-sm font-normal text-slate-500 leading-5">Duration</div>
                           <div class="text-sm md:text-base font-semibold text-slate-950 leading-6">3 nights</div>
                        </div>
                        <div class="min-w-[150px] flex flex-col gap-1">
                           <div class="text-xs md:text-sm font-normal text-slate-500 leading-5">Room Only</div>
                           <div class="text-sm md:text-base font-semibold text-slate-950 leading-6">Standard Room</div>
                        </div>
                        <div class="min-w-[150px] flex flex-col gap-1">
                           <div class="text-xs md:text-sm font-normal text-slate-500 leading-5">Number of Guests</div>
                           <div class="text-sm md:text-base font-semibold text-slate-950 leading-6">2</div>
                        </div>
                     </div>
                  </div>
               </div>

               <!-- Contact Information Card -->
               <div class="card flex flex-col">
                  <div class="p-4 md:p-5 border-b border-slate-200">
                     <h2 class="text-lg md:text-xl font-semibold text-slate-950 leading-7 md:leading-8">Contact Information</h2>
                  </div>
                  <div class="p-4 md:p-5 flex flex-col gap-4 md:gap-6">
                     <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                        <div class="min-w-[150px] sm:min-w-[200px] flex flex-col gap-1">
                           <div class="text-xs md:text-sm font-normal text-slate-500 leading-5">Email address</div>
                           <div class="text-sm md:text-base font-semibold text-slate-950 leading-6 break-words">k@gmail.com</div>
                        </div>
                        <div class="min-w-[150px] sm:min-w-[200px] flex flex-col gap-1">
                           <div class="text-xs md:text-sm font-normal text-slate-500 leading-5">Phone number</div>
                           <div class="text-sm md:text-base font-semibold text-slate-950 leading-6">+32 123456789</div>
                        </div>
                     </div>
                  </div>
               </div>

               <!-- Cancellation Policy Card -->
               <div class="card flex flex-col">
                  <div class="p-4 md:p-5 border-b border-slate-200">
                     <h2 class="text-lg md:text-xl font-semibold text-slate-950 leading-7 md:leading-8">Cancellation Policy</h2>
                  </div>
                  <div class="p-4 md:p-5 flex flex-col gap-4 md:gap-6">
                     <div class="flex flex-col gap-3 md:gap-3.5">
                        <div class="flex items-start gap-2.5">
                           <i data-tabler="check" class="w-5 h-5 text-green-700 shrink-0 mt-0.5" data-size="20"></i>
                           <div class="text-xs md:text-sm font-normal text-slate-900 leading-5">Cancellation allowed up to 24 hours before departure with full refund</div>
                        </div>
                        <div class="flex items-start gap-2.5">
                           <i data-tabler="check" class="w-5 h-5 text-green-700 shrink-0 mt-0.5" data-size="20"></i>
                           <div class="text-xs md:text-sm font-normal text-slate-900 leading-5">Date changes allowed with a fee of €50</div>
                        </div>
                        <div class="flex items-start gap-2.5">
                           <i data-tabler="check" class="w-5 h-5 text-green-700 shrink-0 mt-0.5" data-size="20"></i>
                           <div class="text-xs md:text-sm font-normal text-slate-900 leading-5">No-show charges apply: 100% of ticket value</div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</main>