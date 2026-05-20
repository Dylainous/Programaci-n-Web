using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SchoolGrades.Data;
using SchoolGrades.Models;

namespace SchoolGrades.Controllers;

public class EstudiantesController : Controller
{
    private readonly AppDbContext _context;

    public EstudiantesController(AppDbContext context)
    {
        _context = context;
    }

    public async Task<IActionResult> Index()
    {
        var estudiantes = await _context.Estudiantes
            .OrderBy(e => e.Nombre)
            .ToListAsync();

        return View(estudiantes);
    }

    public IActionResult Insertar()
    {
        return View(new EstudianteCalificacionViewModel());
    }

    [HttpPost]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> Insertar(EstudianteCalificacionViewModel vm)
    {
        vm.Nombre = vm.Nombre?.Trim() ?? string.Empty;
        vm.Cedula = vm.Cedula?.Trim() ?? string.Empty;
        vm.Correo = vm.Correo?.Trim().ToLower() ?? string.Empty;

        ModelState.Clear();
        TryValidateModel(vm);

        if (!ModelState.IsValid)
            return View(vm);

        if (await _context.Estudiantes.AnyAsync(e => e.Cedula == vm.Cedula))
        {
            ModelState.AddModelError("Cedula", "A student with this ID number already exists.");
            return View(vm);
        }

        if (await _context.Estudiantes.AnyAsync(e => e.Correo == vm.Correo))
        {
            ModelState.AddModelError("Correo", "A student with this email already exists.");
            return View(vm);
        }

        if (!ValidarCedulaEcuatoriana(vm.Cedula))
        {
            ModelState.AddModelError("Cedula", "The entered ID number is not valid.");
            return View(vm);
        }

        var estudiante = new Estudiante
        {
            Nombre          = vm.Nombre,
            Cedula          = vm.Cedula,
            Correo          = vm.Correo,
            DeporteFavorito = vm.DeporteFavorito,
            Nota1           = vm.Nota1,
            Nota2           = vm.Nota2,
            Nota3           = vm.Nota3,
            Promedio        = Math.Round((vm.Nota1 + vm.Nota2 + vm.Nota3) / 3, 2),
        };

        _context.Estudiantes.Add(estudiante);
        await _context.SaveChangesAsync();

        TempData["Exito"] = $"Student '{estudiante.Nombre}' was successfully registered.";
        return RedirectToAction(nameof(Index));
    }

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
}