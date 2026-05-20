using Microsoft.AspNetCore.Mvc;

namespace SchoolGrades.Controllers;

public class HomeController : Controller
{
    public IActionResult Index()
    {
        return RedirectToAction("Index", "Estudiantes");
    }
}
