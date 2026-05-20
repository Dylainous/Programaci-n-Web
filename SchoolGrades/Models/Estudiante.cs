using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace SchoolGrades.Models;

[Table("estudiantes")]
public class Estudiante
{
    [Key]
    [Column("id")]
    public int Id { get; set; }

    [Required]
    [Column("nombre")]
    [StringLength(150)]
    public string Nombre { get; set; } = string.Empty;

    [Required]
    [Column("cedula")]
    [StringLength(20)]
    public string Cedula { get; set; } = string.Empty;

    [Required]
    [Column("correo")]
    [StringLength(200)]
    [EmailAddress]
    public string Correo { get; set; } = string.Empty;

    // Navigation property
    public Calificacion? Calificacion { get; set; }
}
