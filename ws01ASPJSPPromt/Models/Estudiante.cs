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

    [Column("deporte_favorito")]
    [StringLength(50)]
    public string? DeporteFavorito { get; set; }

    [Required]
    [Column("nota1")]
    [Range(1, 10)]
    public decimal Nota1 { get; set; }

    [Required]
    [Column("nota2")]
    [Range(1, 10)]
    public decimal Nota2 { get; set; }

    [Required]
    [Column("nota3")]
    [Range(1, 10)]
    public decimal Nota3 { get; set; }

    [Column("promedio")]
    public decimal Promedio { get; set; }
}