using Microsoft.EntityFrameworkCore;
using SchoolGrades.Models;

namespace SchoolGrades.Data;

public class AppDbContext : DbContext
{
    public AppDbContext(DbContextOptions<AppDbContext> options) : base(options) { }

    public DbSet<Estudiante> Estudiantes { get; set; }
    public DbSet<Calificacion> Calificaciones { get; set; }

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        base.OnModelCreating(modelBuilder);

        // One-to-one: Estudiante <-> Calificacion
        modelBuilder.Entity<Estudiante>()
            .HasOne(e => e.Calificacion)
            .WithOne(c => c.Estudiante)
            .HasForeignKey<Calificacion>(c => c.EstudianteId)
            .OnDelete(DeleteBehavior.Cascade);

        // Unique constraint on cedula
        modelBuilder.Entity<Estudiante>()
            .HasIndex(e => e.Cedula)
            .IsUnique();
    }
}
