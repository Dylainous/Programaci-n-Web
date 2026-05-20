using System.ComponentModel.DataAnnotations;

namespace SchoolGrades.Models;

public class EstudianteCalificacionViewModel
{
    // Estudiante fields
    [Required(ErrorMessage = "El nombre es obligatorio")]
    [StringLength(150)]
    [Display(Name = "Nombre completo")]
    public string Nombre { get; set; } = string.Empty;

    [Required(ErrorMessage = "La cédula es obligatoria")]
    [StringLength(20)]
    [Display(Name = "Cédula")]
    public string Cedula { get; set; } = string.Empty;

    [Required(ErrorMessage = "El correo es obligatorio")]
    [EmailAddress(ErrorMessage = "Correo no válido")]
    [StringLength(200)]
    [Display(Name = "Correo electrónico")]
    public string Correo { get; set; } = string.Empty;

    // Calificacion fields
    [Required(ErrorMessage = "La nota 1 es obligatoria")]
    [Range(1, 10, ErrorMessage = "La nota debe estar entre 1 y 10")]
    [Display(Name = "Nota 1")]
    public decimal Nota1 { get; set; }

    [Required(ErrorMessage = "La nota 2 es obligatoria")]
    [Range(1, 10, ErrorMessage = "La nota debe estar entre 1 y 10")]
    [Display(Name = "Nota 2")]
    public decimal Nota2 { get; set; }

    [Required(ErrorMessage = "La nota 3 es obligatoria")]
    [Range(1, 10, ErrorMessage = "La nota debe estar entre 1 y 10")]
    [Display(Name = "Nota 3")]
    public decimal Nota3 { get; set; }
}
