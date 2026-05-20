using System.ComponentModel.DataAnnotations;

namespace SchoolGrades.Models;

public class EstudianteCalificacionViewModel
{
    [Required(ErrorMessage = "The name is required")]
    [StringLength(150, MinimumLength = 3, ErrorMessage = "The name must be between 3 and 150 characters")]
    [RegularExpression(@"^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$", ErrorMessage = "The name can only contain letters and spaces")]
    [Display(Name = "Full name")]
    public string Nombre { get; set; } = string.Empty;

    [Required(ErrorMessage = "The ID number is required")]
    [StringLength(10, MinimumLength = 10, ErrorMessage = "The ID number must contain exactly 10 digits")]
    [RegularExpression(@"^\d{10}$", ErrorMessage = "The ID number can only contain numbers")]
    [Display(Name = "ID number")]
    public string Cedula { get; set; } = string.Empty;

    [Required(ErrorMessage = "The email is required")]
    [EmailAddress(ErrorMessage = "Enter a valid email address")]
    [StringLength(200, ErrorMessage = "The email cannot exceed 200 characters")]
    [Display(Name = "Email address")]
    public string Correo { get; set; } = string.Empty;

    [Required(ErrorMessage = "Grade 1 is required")]
    [Range(1, 10, ErrorMessage = "Grade 1 must be between 1 and 10")]
    [Display(Name = "Grade 1")]
    public decimal Nota1 { get; set; }

    [Required(ErrorMessage = "Grade 2 is required")]
    [Range(1, 10, ErrorMessage = "Grade 2 must be between 1 and 10")]
    [Display(Name = "Grade 2")]
    public decimal Nota2 { get; set; }

    [Required(ErrorMessage = "Grade 3 is required")]
    [Range(1, 10, ErrorMessage = "Grade 3 must be between 1 and 10")]
    [Display(Name = "Grade 3")]
    public decimal Nota3 { get; set; }
}