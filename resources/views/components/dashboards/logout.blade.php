 {{-- Logout --}}
 <div class="p-4 border-t border-white/10">
     <form method="POST" action="{{ route('logout') }}">
         @csrf
         <button type="submit"
             class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-teal-100 hover:bg-white/10 text-sm transition">
             <i class="fas fa-sign-out-alt w-4 text-center"></i>
             تسجيل الخروج
         </button>
     </form>
 </div>
