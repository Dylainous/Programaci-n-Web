using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace SchoolGrades.Models;

[Table("calificaciones")]
public class Calificacion
{
    [Key]
    [Column("id")]
    public int Id { get; set; }

    [Required]
    [Column("estudiante_id")]
    public int EstudianteId { get; set; }

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

    [NotMapped]
    public decimal Promedio => Math.Round((Nota1 + Nota2 + Nota3) / 3, 2);

    // Navigation property
    [ForeignKey("EstudianteId")]
    public Estudiante? Estudiante { get; set; }
}
