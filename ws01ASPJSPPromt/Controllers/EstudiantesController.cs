[HttpPost]
[ValidateAntiForgeryToken]
public async Task<IActionResult> Insertar(EstudianteCalificacionViewModel vm)
{
    // Server-side: trim whitespace before validation
    vm.Nombre = vm.Nombre?.Trim() ?? string.Empty;
    vm.Cedula = vm.Cedula?.Trim() ?? string.Empty;
    vm.Correo = vm.Correo?.Trim().ToLower() ?? string.Empty;

    // Re-validate after trimming
    ModelState.Clear();
    TryValidateModel(vm);

    if (!ModelState.IsValid)
        return View(vm);

    // Verify: check duplicate cédula
    bool cedulaExiste = await _context.Estudiantes
        .AnyAsync(e => e.Cedula == vm.Cedula);
    if (cedulaExiste)
    {
        ModelState.AddModelError("Cedula", "A student with this ID number already exists.");
        return View(vm);
    }

    // Verify: check duplicate correo
    bool correoExiste = await _context.Estudiantes
        .AnyAsync(e => e.Correo == vm.Correo);
    if (correoExiste)
    {
        ModelState.AddModelError("Correo", "A student with this email already exists.");
        return View(vm);
    }

    // Verify: Ecuadorian cédula algorithm
    if (!ValidarCedulaEcuatoriana(vm.Cedula))
    {
        ModelState.AddModelError("Cedula", "The entered ID number is not valid.");
        return View(vm);
    }

    var estudiante = new Estudiante
    {
        Nombre = vm.Nombre,
        Cedula = vm.Cedula,
        Correo = vm.Correo,
    };

    _context.Estudiantes.Add(estudiante);
    await _context.SaveChangesAsync();

    var calificacion = new Calificacion
    {
        EstudianteId = estudiante.Id,
        Nota1 = vm.Nota1,
        Nota2 = vm.Nota2,
        Nota3 = vm.Nota3,
    };

    _context.Calificaciones.Add(calificacion);
    await _context.SaveChangesAsync();

    TempData["Exito"] = $"Student '{estudiante.Nombre}' was successfully registered.";
    return RedirectToAction(nameof(Index));
}

// Ecuadorian cédula verification algorithm
private static bool ValidarCedulaEcuatoriana(string cedula)
{
    if (cedula.Length != 10) return false;

    int provincia = int.Parse(cedula.Substring(0, 2));
    if (provincia < 1 || provincia > 24) return false;

    int[] coeficientes = { 2, 1, 2, 1, 2, 1, 2, 1, 2 };
    int suma = 0;

    for (int i = 0; i < 9; i++)
    {
        int digito = int.Parse(cedula[i].ToString()) * coeficientes[i];
        if (digito >= 10) digito -= 9;
        suma += digito;
    }

    int digitoVerificador = (10 - (suma % 10)) % 10;
    return digitoVerificador == int.Parse(cedula[9].ToString());
}