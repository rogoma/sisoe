<table class="table table-bordered table-hover mt-3">
    <thead class="thead-dark">
      <tr>
          <th>Departamento</th>
          <th>Distrito</th>                
          <th>Localidad</th>
      </tr>
    </thead>
    <tbody>
        {{-- @foreach ($orders as $f)
            <td> {{ $f->departamento }}</td>
            <td> {{ $f->distrito }}</td>   
            <td> {{ $f->localidad }}</td>
        @endforeach --}}

        @foreach($orders AS $f)
            <tr>
                <td> {{ $f->departamento }}</td>
                <td> {{ $f->distrito }}</td>   
                <td> {{ $f->localidad }}</td>                
            </tr>
            @endforeach
    </tbody>
  </table>