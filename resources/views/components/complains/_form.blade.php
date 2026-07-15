  <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

      <div class="h-1 bg-blue-600"></div>
      <x-shared.errors />
      <form action=" {{ route('complains.store') }}" method="POST" class="p-6 sm:p-8 space-y-8">
          @csrf
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <x-complains._form.patient />
              <x-complains._form.department />
              <x-complains._form.doctor />
              <x-complains._form.visit_date />
              <x-complains._form.issue_type />
              <x-complains._form.severity />
          </div>
          <x-complains._form.description />
          <x-complains._form.status_section />
          <x-complains._form.resolution_notes />
          <x-complains._form.actions />

      </form>
  </div>
