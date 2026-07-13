  <div x-show="showModel" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
      @keydown.escape.window="showModel = false">

      <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg" @click.outside="showModel = false">

          <x-schedules.model.header />

          <form
              :action="addMode
                  ?
                  '{{ route('schedules.store') }}' :
                  '{{ url('schedules') }}/' + editSchedule.id"
              method="POST">
              <template x-if="!addMode">
                  <input type="hidden" name="_method" value="PUT">
              </template>
              @csrf
              <x-schedules._form />
          </form>
      </div>
  </div>
