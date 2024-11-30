const categoryList = document.getElementById("categories");
const courseList = document.getElementById("courses");
const toggleSidebarButton = document.getElementById("toggle-sidebar");

function buildCategoryTree(categories, parentId = null) {
  return categories
    .filter((category) => category.parent === parentId)
    .map((category) => {
      const childCategories = buildCategoryTree(categories, category.id);
      const courseCount = calculateCourseCount(category.id, childCategories);
      return { ...category, childCategories, courseCount };
    });
}

function calculateCourseCount(categoryId, childCategories) {
  const directCourses = courses.filter(
    (course) => course.category_id === categoryId
  ).length;
  const childCourseCount = childCategories.reduce(
    (acc, child) => acc + calculateCourseCount(child.id, child.childCategories),
    0
  );
  return directCourses + childCourseCount;
}

function renderCategories(categoryTree, depth = 0) {
  if (depth >= 5) {
    return;
  }

  const ul = document.createElement("ul");
  ul.classList.add("category-list");

  categoryTree.forEach((c) => {
    const li = document.createElement("li");
    li.textContent = `${c.name} ${
      c.courseCount > 0 ? `(${c.courseCount})` : ""
    }`;

    li.addEventListener("click", (event) => {
      event.stopPropagation();
      filterCoursesByCategory(c.id, c.childCategories);
    });

    if (c.childCategories.length > 0) {
      const div = document.createElement("div");
      div.classList.add("collapse-category");

      const button = document.createElement("button");
      button.classList.add("collapse-btn");

      const image = document.createElement("img");
      image.src = "images/collapse-arrow.png";
      image.alt = "Collapse arrow";
      image.classList.add("collapse-img");
      button.appendChild(image);

      const newDiv = document.createElement("div");
      newDiv.style = `display: flex; gap: 0.25rem; align-items: center; padding-left: ${
        15 * depth
      }px`;
      newDiv.appendChild(button);
      newDiv.appendChild(li);
      div.appendChild(newDiv);

      const childrenUl = renderCategories(c.childCategories, depth + 1);
      div.appendChild(childrenUl);

      button.addEventListener("click", (event) => {
        event.stopPropagation();
        const isCollapsed = childrenUl.style.display === "none";
        childrenUl.style.display = isCollapsed ? "block" : "none";
        image.style.transform = isCollapsed ? "rotate(0deg)" : "rotate(-90deg)";
      });

      ul.appendChild(div);
    } else {
      li.style.paddingLeft = `${depth * 30}px`;
      ul.appendChild(li);
    }
  });

  return ul;
}

function filterCoursesByCategory(categoryId, childCategories) {
  const allCategoryIds = getAllCategoryIds(categoryId, childCategories);

  const filteredCourses = courses.filter((course) =>
    allCategoryIds.includes(course.category_id)
  );

  renderCourses(filteredCourses);
}

function getAllCategoryIds(categoryId, childCategories) {
  let ids = [categoryId];

  childCategories.forEach((child) => {
    ids = ids.concat(getAllCategoryIds(child.id, child.childCategories));
  });

  return ids;
}

function renderCourses(courseData) {
  courseList.innerHTML = "";

  if (courseData.length === 0) {
    const h3 = document.createElement("h3");
    h3.style = "min-height: 500px; display: grid; place-content: center";
    h3.innerText = "No courses found.";
    courseList.appendChild(h3);
    return;
  }

  courseData.forEach((course) => {
    const card = document.createElement("div");
    card.className = "course-card";
    card.innerHTML = `
      <img src="${course.image_preview}" alt="${course.title}">
      <span style="position: absolute; right: 15px; top: 5px; background-color: var(--primary-color); color: white; font-size: 20px; padding: 4px 6px;border-radius: var(--border-radius)">${getRootCategoryName(
        course.category_id
      )}</span>
      <div>
        <h3>${course.title}</h3>
        <p>${course.description}</p>
        <span>Category: ${getCategoryName(course.category_id)}</span>
      </div>
    `;
    courseList.appendChild(card);
  });
}

function getCategoryName(categoryId) {
  const category = categories.find((cat) => cat.id === categoryId);
  return category ? category.name : "Unknown";
}

function getRootCategoryName(categoryId) {
  const category = categories.find((cat) => cat.id === categoryId);

  if (!category) {
    return "Unknown";
  }

  if (category.parent === null) {
    return category.name;
  }

  return getRootCategoryName(category.parent);
}

toggleSidebarButton.addEventListener("click", () => {
  categoryList.classList.toggle("closed");
  toggleSidebarButton.querySelector("img").classList.toggle("flipped");
});

// Initialize
const categoryTree = buildCategoryTree(categories);
const ul = renderCategories(categoryTree, 0);
categoryList.appendChild(ul);
renderCourses(courses);
