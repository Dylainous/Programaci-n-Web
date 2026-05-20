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

    // GET: /Estudiantes/Index  — lista de calificaciones
    public async Task<IActionResult> Index()
    {
        var estudiantes = await _context.Estudiantes
            .Include(e => e.Calificacion)
            .OrderBy(e => e.Nombre)
            .ToListAsync();

        return View(estudiantes);
    }

    // GET: /Estudiantes/Insertar
    public IActionResult Insertar()
    {
        return View(new EstudianteCalificacionViewModel());
    }

    // POST: /Estudiantes/Insertar
    [HttpPost]
    [ValidateAntiForgeryToken]
    public async Task<IActionResult> Insertar(EstudianteCalificacionViewModel vm)
    {
        if (!ModelState.IsValid)
            return View(vm);

        // Check duplicate cedula
        bool cedulaExiste = await _context.Estudiantes
            .AnyAsync(e => e.Cedula == vm.Cedula);

        if (cedulaExiste)
        {
            ModelState.AddModelError("Cedula", "Ya existe un estudiante con esa cédula.");
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

        TempData["Exito"] = $"Estudiante '{estudiante.Nombre}' registrado correctamente.";
        return RedirectToAction(nameof(Index));
    }
}
