      <a href="#"
          class="flex items-center gap-3 px-4 py-3 rounded-xl text-teal-100 hover:bg-white/10 text-sm transition">
          <i class="fas fa-bell w-4 text-center"></i>
          الإشعارات
          @if (($unreadCount ?? 0) > 0)
              <span
                  class="mr-auto bg-orange-400 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                  {{ $unreadCount }}
              </span>
          @endif
      </a>
