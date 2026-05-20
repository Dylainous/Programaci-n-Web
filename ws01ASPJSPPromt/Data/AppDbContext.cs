using Microsoft.EntityFrameworkCore;
using SchoolGrades.Models;

namespace SchoolGrades.Data;

public class AppDbContext : DbContext
{
    public AppDbContext(DbContextOptions<AppDbContext> options) : base(options) { }

    public DbSet<Estudiante> Estudiantes { get; set; }

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        base.OnModelCreating(modelBuilder);

        modelBuilder.Entity<Estudiante>()
            .HasIndex(e => e.Cedula)
            .IsUnique();

        modelBuilder.Entity<Estudiante>()
            .HasIndex(e => e.Correo)
            .IsUnique();
    }
}