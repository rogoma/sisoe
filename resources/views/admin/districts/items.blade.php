<table class="table table-bordered table-hover mt-3">
    <thead class="thead-dark">
      <tr>
          <th>Departamento</th>
          <th>Distrito</th>                          
      </tr>
    </thead>
    <tbody>
        {{-- @foreach ($localities as $f)
            <td> {{ $f->departamento }}</td>
            <td> {{ $f->distrito }}</td>   
            <td> {{ $f->localidad }}</td>
        @endforeach --}}

        @foreach($districts AS $f)
            <tr>
                <td> {{ $f->departamento }}</td>
                <td> {{ $f->distrito }}</td>                   
            </tr>
            @endforeach
    </tbody>
  </table>