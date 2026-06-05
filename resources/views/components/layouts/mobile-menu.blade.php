 <!-- Mobile Menu + Click Outside -->
 <div x-show="open" x-transition x-cloak class="lg:hidden fixed top-16 left-0 right-0 bg-white shadow-lg z-[9998]"
     @click.outside="open = false">
     <div class="flex flex-col gap-3 mt-3 ps-4">

         <a  @click="open = false" href="/" class="py-2 font-semibold text-gray-700">الرئيسية</a>
         <a  @click="open = false" href="#clinics" class="py-2 font-semibold text-gray-700">العيادات</a>
         <a  @click="open = false" href="#specialities" class="py-2 font-semibold text-gray-700">التخصصات</a>
         <a  @click="open = false" href="#doctors" class="py-2 font-semibold text-gray-700">الأطباء</a>

         <div class="border-t pt-3 mt-2 flex flex-col gap-2">

             @auth
                 <a href="{{ route('dashboard') }}" class="bg-emerald-600 text-white text-center py-2 rounded-xl font-bold">
                     لوحة التحكم
                 </a>

                 <form method="POST" action="{{ route('logout') }}">
                     @csrf
                     <button type="submit" class="bg-red-600 text-white w-full py-2 rounded-xl font-bold">
                         خروج
                     </button>
                 </form>
             @endauth

             @guest
                 <a href="{{ route('login') }}" class="text-center py-2 font-semibold text-gray-700">
                     دخول
                 </a>

                 <a href="{{ route('register') }}" class="bg-emerald-600 text-white text-center py-2 rounded-xl font-bold">
                     إنشاء حساب
                 </a>
             @endguest

         </div>
     </div>
 </div>
